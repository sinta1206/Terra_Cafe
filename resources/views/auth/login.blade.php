<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TERRA Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="mb-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Selamat datang di Terra</p>
            <h1 class="text-2xl font-bold text-gray-900">Senang kamu kembali.</h1>
            <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan pesanan.</p>
        </div>

        <div class="flex bg-gray-100 p-1 rounded-xl mb-6">
            <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-sm font-medium bg-white text-gray-900 rounded-lg shadow-sm">Masuk</a>
            <a href="{{ route('register') }}" class="flex-1 py-2 text-center text-sm font-medium text-gray-500 hover:text-gray-900">Daftar</a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-800 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="Minimal 4 karakter" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-800 text-sm">
            </div>

            <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-lg font-medium hover:bg-black transition">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
