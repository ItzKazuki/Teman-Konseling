<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
      
      <h3 class="text-2xl font-semibold text-gray-800">Daftar Pengguna Sistem</h3>
      
      <div class="flex items-center space-x-3">
        
        <button 
          @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <button 
          @click="handleCreate"
          class="flex items-center px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
        >
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Pengguna
        </button>
      </div>
    </div>

    <div v-if="isFilterVisible" class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
        <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Pengguna</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input 
            type="text" 
            v-model="filterForm.search" 
            placeholder="Cari Nama, Email, atau NIP"
            class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" 
          />
          <select 
            v-model="filterForm.role" 
            class="form-select rounded-lg text-sm border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Semua Peran (Role)</option>
            <option value="guru">Guru</option>
            <option value="bk">BK/Konselor</option>
            <option value="admin">Administrator</option>
          </select>
          <select 
            v-model="filterForm.isVerified" 
            class="form-select rounded-lg text-sm border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Status Verifikasi Email</option>
            <option value="true">Sudah Diverifikasi</option>
            <option value="false">Belum Diverifikasi</option>
          </select>
          <button @click="applyFilter" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 md:col-span-3 lg:col-span-1">
            Terapkan Filter
          </button>
        </div>
    </div>


    <div class="overflow-x-auto border border-gray-200 rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nama & Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Jabatan
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Peran (Role)
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Verifikasi
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          
          <tr v-if="filteredUsers.length === 0">
            <td colspan="5" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
              Tidak ada pengguna yang ditemukan.
            </td>
          </tr>

          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-blue-50/50 transition-colors duration-100">
            
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
              <div class="text-xs text-gray-500">{{ user.email }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.jabatan }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span 
                :class="getRoleClass(user.role)" 
                class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full uppercase"
              >
                {{ user.role }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center">
                <span v-if="user.email_verified_at" class="text-green-600 text-sm">
                    <Icon name="tabler:check" class="w-5 h-5 inline-block" />
                </span>
                <span v-else class="text-red-500 text-sm">
                    <Icon name="tabler:x" class="w-5 h-5 inline-block" />
                </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <button @click="handleEdit(user.id)" class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </button>
              <button @click="handleDelete(user.id, user.name)" class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-100/50">
                <Icon name="tabler:trash" class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-end">
        <div class="flex items-center text-sm text-gray-600 space-x-2">
          <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 transition-colors" disabled>
            <Icon name="tabler:chevron-left" class="w-4 h-4" />
          </button>
          <span class="px-2 py-1">Halaman 1 dari 1</span>
          <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors" disabled>
            <Icon name="tabler:chevron-right" class="w-4 h-4" />
          </button>
        </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';

// ==============================================
// 1. DATA SIMULASI (Diganti dengan fetch API sesungguhnya)
// ==============================================
const rawUserData = ref([
    {
      "id": "019af420-0535-7005-8188-872b3274c131",
      "nip": "198001012005011001",
      "name": "Ibu Lia Permata",
      "email": "lia.bk@sekolah.sch.id",
      "role": "bk",
      "jabatan": "Koordinator BK",
      "email_verified_at": "2025-12-06T14:45:30.000000Z",
      "created_at": "2025-12-06T14:45:30.000000Z",
      "updated_at": "2025-12-06T14:45:30.000000Z"
    },
    {
      "id": "019af420-06ce-70e2-b804-fd6e2beeb198",
      "nip": "198505152010022002",
      "name": "Pak Bima Sakti",
      "email": "bima.bk@sekolah.sch.id",
      "role": "bk",
      "jabatan": "Staf BK",
      "email_verified_at": null, // Simulasi email belum diverifikasi
      "created_at": "2025-12-06T14:45:30.000000Z",
      "updated_at": "2025-12-06T14:45:30.000000Z"
    },
    {
      "id": "019af420-0852-71ff-8be4-943fd0da3b3f",
      "nip": "197510202000031003",
      "name": "Bu Tina Agustina",
      "email": "tina.agustina@sekolah.sch.id",
      "role": "guru",
      "jabatan": "Wali Kelas X RPL 1",
      "email_verified_at": "2025-12-06T14:45:31.000000Z",
      "created_at": "2025-12-06T14:45:31.000000Z",
      "updated_at": "2025-12-06T14:45:31.000000Z"
    },
    {
      "id": "019af420-09d9-7169-89b0-c9fc08af9c69",
      "nip": "199003052015042004",
      "name": "Pak Rahmat Hidayat",
      "email": "rahmat.hidayat@sekolah.sch.id",
      "role": "guru",
      "jabatan": "Guru Mapel",
      "email_verified_at": "2025-12-06T14:45:31.000000Z",
      "created_at": "2025-12-06T14:45:31.000000Z",
      "updated_at": "2025-12-06T14:45:31.000000Z"
    }
]);

// ==============================================
// 2. LOGIKA FILTER
// ==============================================
const isFilterVisible = ref(false);

const filterForm = reactive({
  search: '',
  role: '', // bk, guru, admin, etc.
  isVerified: '', // true, false
});

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const applyFilter = () => {
  console.log(`Menerapkan filter: Cari='${filterForm.search}', Role='${filterForm.role}', Verified='${filterForm.isVerified}'`);
  alert('Filter diterapkan! (Cek console log untuk detail filter)');
};

// Computed property untuk menampilkan data setelah filter di frontend (untuk simulasi)
const filteredUsers = computed(() => {
  let data = rawUserData.value;

  if (filterForm.search) {
    const searchLower = filterForm.search.toLowerCase();
    data = data.filter(item =>
      item.name.toLowerCase().includes(searchLower) ||
      item.email.toLowerCase().includes(searchLower) ||
      item.nip.includes(filterForm.search)
    );
  }

  if (filterForm.role) {
    data = data.filter(item => item.role === filterForm.role);
  }

  if (filterForm.isVerified !== '') {
    data = data.filter(item => {
        if (filterForm.isVerified === 'true') {
            return !!item.email_verified_at; // Cek apakah ada nilainya (true)
        } else if (filterForm.isVerified === 'false') {
            return !item.email_verified_at; // Cek apakah null (false)
        }
        return true;
    });
  }

  return data;
});

// ==============================================
// 3. LOGIKA AKSI (CRUD) & STYLING
// ==============================================
const handleCreate = () => {
  alert('Navigasi ke halaman Tambah Pengguna Baru');
  // Contoh: navigateTo('/users/create');
};

const handleEdit = (id) => {
  alert(`Navigasi ke halaman Edit Pengguna ID: ${id}`);
  // Contoh: navigateTo(`/users/${id}/edit`);
};

const handleDelete = (id, name) => {
  if (confirm(`Apakah Anda yakin ingin menghapus pengguna: ${name}?`)) {
    alert(`Permintaan HAPUS berhasil dikirim untuk ID: ${id}`);
    // Di dunia nyata: Panggil API delete dan muat ulang data.
  }
};

const getRoleClass = (role) => {
    switch (role) {
        case 'bk':
            return 'bg-purple-100 text-purple-800';
        case 'guru':
            return 'bg-indigo-100 text-indigo-800';
        case 'admin':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>