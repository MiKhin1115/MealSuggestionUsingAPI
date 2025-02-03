<form action="{{ route('admin.recipes.store') }}" method="POST" class="space-y-6">
    @csrf
    <div>
        <label>Title</label>
        <input type="text" name="title" required>
    </div>
    <div>
        <label>Description</label>
        <textarea name="description" required></textarea>
    </div>
    <div>
        <label>Image URL</label>
        <input type="url" name="image_url" required>
    </div>
    <div>
        <label>Meal Type</label>
        <select name="meal_type" required>
            <option value="Breakfast">Breakfast</option>
            <option value="Lunch">Lunch</option>
            <option value="Dinner">Dinner</option>
            <option value="Supper">Supper</option>
        </select>
    </div>
    <!-- Add other fields... -->
    <button type="submit">Add Recipe</button>
</form> 