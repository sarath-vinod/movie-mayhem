<div class="form-group">
  <label class="visually-hidden" for="title">Title</label>
  <input
    type="text"
    class="form-control"
    id="title"
    name="title"
    placeholder="Title"
    required
    value="<?php echo $movie['title'] ?? ''; ?>">
  <div class="error text-danger">
    <?php echo $errors['title'] ?? ''; ?>
  </div>
</div>

<div class="form-group">
  <label class="visually-hidden" for="director">Director</label>
  <input
    type="text"
    class="form-control"
    id="director"
    name="director"
    placeholder="Director"
    required
    value="<?php echo $movie['director'] ?? ''; ?>">
  <div class="error text-danger">
    <?php echo $errors['director'] ?? ''; ?>
  </div>
</div>

<div class="form-group">
  <label class="visually-hidden" for="year">Year</label>
  <input
    type="number"
    class="form-control"
    id="year"
    name="year"
    placeholder="Year"
    required
    value="<?php echo $movie['year'] ?? ''; ?>">
  <div class="error text-danger">
    <?php echo $errors['year'] ?? ''; ?>
  </div>
</div>

<div class="form-group">
  <label class="visually-hidden" for="genre">Genre</label>
  <select class="form-select" id="genre" name="genre">
    <option value="">Select a Genre</option>
    <?php foreach ($genres as $genre) : ?>
      <option value="<?php echo $genre; ?>"
        <?php if (isset($movie['genre']) && $genre === $movie['genre']) : ?> selected <?php endif; ?>>
        <?php echo $genre; ?>
      </option>
    <?php endforeach; ?>
  </select>
  <div class="error text-danger">
    <?php echo $errors['genre'] ?? ''; ?>
  </div>
</div>

<div class="form-group">
  <label class="visually-hidden" for="poster">Poster</label>
  <input
    type="file"
    class="form-control"
    id="poster"
    name="poster"
    accept=".jpg">
</div>