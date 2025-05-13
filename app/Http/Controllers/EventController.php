<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::with(['user', 'category', 'tags'])
            ->where(function($query) {
                // Admin can see all events
                if (!Auth::user()->isAdmin()) {
                    // Organizers can see their own events, users can see public events
                    if (Auth::user()->role === 'organizer') {
                        $query->where('user_id', Auth::id())
                              ->orWhere('is_private', false);
                    } else {
                        $query->where('is_private', false)
                              ->where('status', 'published');
                    }
                }
            })
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        // Get categories and tags for search filters
        $categories = Category::all();
        $tags = Tag::all();

        return view('events.index', compact('events', 'categories', 'tags'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Check if user is allowed to create events
        if (Auth::user()->role !== 'organizer' && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You need to be an organizer to create events.');
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('events.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        // Check if user is allowed to create events
        if (Auth::user()->role !== 'organizer' && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You need to be an organizer to create events.');
        }

        // Validate incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_featured' => 'boolean',
            'is_private' => 'boolean',
            'access_code' => 'nullable|string|required_if:is_private,1',
            'custom_fields' => 'nullable|json',
            'event_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $imagePath = $request->file('event_image')->store('events', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Set additional fields
        $validated['user_id'] = Auth::id();
        $validated['status'] = $request->has('publish') ? 'published' : 'draft';

        // Create the event
        $event = Event::create($validated);

        // Attach tags
        if ($request->has('tags')) {
            $event->tags()->attach($request->tags);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Check if user can view this event
        if ($event->is_private && $event->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'This event is private.');
        }

        $event->load(['user', 'category', 'tags', 'registrations']);

        // Check if user is registered
        $isRegistered = $event->registrations->contains('user_id', Auth::id());

        // Check if event is full
        $isFull = $event->max_participants && $event->registrations->count() >= $event->max_participants;

        return view('events.show', compact('event', 'isRegistered', 'isFull'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Check if user can edit this event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You can only edit your own events.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        $selectedTags = $event->tags->pluck('id')->toArray();

        return view('events.edit', compact('event', 'categories', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Check if user can update this event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You can only update your own events.');
        }

        // Validate incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_featured' => 'boolean',
            'is_private' => 'boolean',
            'access_code' => 'nullable|string|required_if:is_private,1',
            'custom_fields' => 'nullable|json',
            'event_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        // Handle image upload
        if ($request->hasFile('event_image')) {
            // Delete old image if exists
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }

            $imagePath = $request->file('event_image')->store('events', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Update status if publishing
        if ($request->has('publish') && $event->status === 'draft') {
            $validated['status'] = 'published';
        }

        // Update the event
        $event->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        } else {
            $event->tags()->detach();
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Check if user can delete this event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You can only delete your own events.');
        }

        // Delete image if exists
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        // Delete the event (soft delete)
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Update event status (publish, cancel, etc).
     */
    public function updateStatus(Request $request, Event $event)
    {
        // Check if user can update this event
        if ($event->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('events.index')
                ->with('error', 'You can only update your own events.');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event status updated successfully!');
    }

    /**
     * Search events based on various criteria.
     */
    public function search(Request $request)
    {
        $query = Event::with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->where('is_private', false);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('tags')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->whereIn('tags.id', $request->tags);
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(10);
        $categories = Category::all();
        $tags = Tag::all();

        return view('events.index', compact('events', 'categories', 'tags'));
    }

    /**
     * Show user's dashboard with their events.
     */
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'organizer' || $user->role === 'admin') {
            // For organizers, show their own events
            $eventsQuery = $user->role === 'admin'
                ? Event::query()  // Admin can see all events
                : Event::where('user_id', $user->id);  // Organizer sees only their events

            $events = $eventsQuery->withCount('registrations')
                ->orderBy('start_date', 'desc')
                ->paginate(5);

            $upcomingEvents = $events->filter(function($event) {
                return $event->start_date > now();
            });

            $pastEvents = $events->filter(function($event) {
                return $event->start_date <= now();
            });

            return view('events.dashboard', compact('events', 'upcomingEvents', 'pastEvents'));
        }

        return redirect()->route('events.index')
            ->with('error', 'You need to be an organizer to access the dashboard.');
    }
}
