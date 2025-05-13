<!DOCTYPE html>
<html>

<head>
    <title>Donate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: sans-serif;
            max-width: 500px;
            margin: auto;
            padding: 20px;
        }

        input,
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

    <h2>Donate Now</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('donation.handle') }}">
        @csrf
        <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
        <input type="number" name="amount" placeholder="Amount (USD)" value="{{ old('amount') }}" min="1" required>
        <button type="submit">Donate</button>
    </form>

    @if(request('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thank you!',
                text: 'Your donation was successful.',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = "{{ route('donation.form') }}";
            });
        </script>
    @elseif(request('canceled'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Cancelled',
                text: 'Payment was cancelled.',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = "{{ route('donation.form') }}";
            });
        </script>
    @endif

</body>

</html>
