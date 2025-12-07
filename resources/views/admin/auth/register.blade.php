<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Register</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
      <h2 class="text-2xl mb-6">Admin Register</h2>

      @if($errors->any())
        <div class="mb-4 text-red-600">
          <ul>
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.register.post') }}">
        @csrf
        <label class="block mb-2">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border p-2 mb-4">

        <label class="block mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 mb-4">

        <label class="block mb-2">Password</label>
        <input type="password" name="password" required class="w-full border p-2 mb-4">

        <label class="block mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" required class="w-full border p-2 mb-4">

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded">Register</button>
      </form>

      <p class="mt-4 text-sm">
        Already have an account? <a href="{{ route('admin.login') }}" class="text-blue-600">Login</a>
      </p>
    </div>
  </div>
</body>
</html>
