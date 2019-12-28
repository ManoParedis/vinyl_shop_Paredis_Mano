@csrf
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Name"
           minlength="3"
           required
           value="{{ old('name', $user->name) }}">
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <label for="email">Email</label>
    <input type="text" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           placeholder="email"
           minlength="3"
           required
           value="{{ old('email', $user->email) }}">
    @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mx-auto row py-2">
    <div class="form-check col">
        <input class="form-check-input @error('email') is-invalid @enderror" type="checkbox" name="active" value="{{old('active',$user->active)}}" id="active">
        <label class="form-check-label" for="defaultCheck1">
            Active
        </label>
        @error('active')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-check col">
        <input class="form-check-input @error('email') is-invalid @enderror" type="checkbox" name="admin" value="{{old('admin',$user->admin)}}" id="admin">
        <label class="form-check-label" for="defaultCheck1">
            Admin
        </label>
        @error('admin')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<button type="submit" class="btn btn-success">Save user</button>