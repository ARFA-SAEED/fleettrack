@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex justify-center py-10">
        <div class="w-full max-w-8xl bg-white p-6">
            <h2 class="text-2xl font-bold text-blue-600 mb-6 text-center">Settings - Add Multiple Users</h2>

            <!-- Success / Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add Users Form -->
            <form method="POST" action="{{ route('settings.addUsers') }}">
                @csrf
                <div id="users-container">
                    <div
                        class="user-row grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <input type="text" name="users[0][name]" placeholder="Name" required
                            class="border rounded px-3 py-2 w-full">
                        <input type="email" name="users[0][email]" placeholder="Email" required
                            class="border rounded px-3 py-2 w-full">
                        <input type="password" name="users[0][password]" placeholder="Password" required
                            class="border rounded px-3 py-2 w-full">

                        <button type="submit"
                            class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600 transition">Submit
                            User</button>
                    </div>
                </div>
            </form>

            <!-- Existing Users Table -->
            <div class="mt-12">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Existing Users</h3>
                @if($users->isEmpty())
                    <p class="text-gray-500">No users found.</p>
                @else
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-4 py-2 border">ID</th>
                                    <th class="px-4 py-2 border">Name</th>
                                    <th class="px-4 py-2 border">Email</th>
                                    <th class="px-4 py-2 border text-center">Status</th>

                                    <th class="px-4 py-2 border">Created At</th>
                                    <th class="px-4 py-2 border text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="bg-white @if($user->email === 'admin@admin.com') bg-gray-100 @endif">
                                        <td class="px-4 py-2 border">{{ $user->id }}</td>
                                        <td class="px-4 py-2 border">{{ $user->name }}</td>
                                        <td class="px-4 py-2 border">{{ $user->email }}</td>
                                        <td class="px-4 py-2 border text-center">
                                            @if($user->is_active)
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Active</span>
                                            @else
                                                <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-sm">Paused</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2 border">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2 border text-center">
                                            <div class="flex gap-2 justify-center flex-wrap">
                                                @if($user->email !== 'admin@admin.com')
                                                    <button onclick="editUser({{ $user->id }})"
                                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</button>
                                                    <button onclick="confirmDelete({{ $user->id }})"
                                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                                                    <form action="{{ route('settings.pauseUser', $user->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="{{ $user->is_active ? 'bg-blue-500' : 'bg-green-500' }} text-white px-3 py-1 rounded hover:opacity-80">
                                                            {{ $user->is_active ? 'Pause' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500 text-sm italic">Admin protected</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="modalOverlay" class="fixed inset-0 hidden z-[9999] bg-black bg-opacity-90 flex items-center justify-center">
    </div>

    <!-- Edit Modal -->
    <div id="editModalTemplate"
        class="hidden bg-white w-full max-w-md p-6 rounded-lg shadow-lg transform transition-transform scale-95">
        <h3 class="text-xl font-semibold mb-4 text-blue-600">Edit User</h3>
        <form id="editFormTemplate">
            @csrf
            <input type="hidden" id="editUserId">
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" id="editUserName" class="border rounded w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block">Email</label>
                <input type="email" id="editUserEmail" class="border rounded w-full px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block">Password</label>
                <input type="password" id="editUserPassword" placeholder="Leave blank to keep current"
                    class="border rounded w-full px-3 py-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>

    <script>
        const overlay = document.getElementById('modalOverlay');

        function openModal(type) {
            overlay.innerHTML = '';
            let content;
            if (type === 'edit') content = document.getElementById('editModalTemplate').cloneNode(true);
            else return;
            content.classList.remove('hidden');
            overlay.appendChild(content);
            overlay.classList.remove('hidden');
            setTimeout(() => content.classList.remove('scale-95'), 10);
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modalContent = overlay.firstChild;
            modalContent.classList.add('scale-95');
            setTimeout(() => overlay.classList.add('hidden'), 150);
            overlay.innerHTML = '';
            document.body.style.overflow = '';
        }

        // Edit user
        function editUser(id) {
            fetch(`/settings/edit-user/${id}`)
                .then(res => res.json())
                .then(user => {
                    openModal('edit');
                    const modal = overlay.firstChild;
                    modal.querySelector('#editUserId').value = id;
                    modal.querySelector('#editUserName').value = user.name;
                    modal.querySelector('#editUserEmail').value = user.email;
                    modal.querySelector('#editUserPassword').value = '';


                    const form = modal.querySelector('#editFormTemplate');
                    form.onsubmit = function (e) {
                        e.preventDefault();
                        const name = modal.querySelector('#editUserName').value;
                        const email = modal.querySelector('#editUserEmail').value;
                        const password = modal.querySelector('#editUserPassword').value;


                        fetch(`/settings/update-user/${id}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ name, email, password })
                        }).then(() => location.reload());
                    }
                });
        }
    </script>
@endsection