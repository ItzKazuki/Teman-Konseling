<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">

      <h3 class="text-2xl font-semibold text-gray-800">Data Kelas</h3>

      <div class="flex items-center space-x-3">

        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <button @click="handleCreate"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Baru
        </button>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Lanjutan</h4>
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <input type="text" v-model="filterForm.search" placeholder="Cari berdasarkan Nama atau Deskripsi"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full sm:w-auto focus:ring-primary-500 focus:border-primary-500" />
        <select v-model="filterForm.level"
          class="form-select rounded-lg text-sm border-gray-300 shadow-sm w-full sm:w-auto focus:ring-primary-500 focus:border-primary-500">
          <option value="">Semua Level (X, XI, XII)</option>
          <option value="X">Kelas X</option>
          <option value="XI">Kelas XI</option>
          <option value="XII">Kelas XII</option>
        </select>
        <button @click="applyFilter"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 w-full sm:w-auto">
          Terapkan
        </button>
      </div>
    </div>


    <div class="overflow-x-auto border border-gray-200 rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nama Kelas
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Deskripsi
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Wali Kelas
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">

          <tr v-if="filteredClasses.length === 0">
            <td colspan="4" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
              Tidak ada data kelas yang ditemukan.
            </td>
          </tr>

          <tr v-for="classItem in filteredClasses" :key="classItem.id"
            class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ classItem.name }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.description }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.homeroom_teacher || '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <button @click="handleEdit(classItem.id)"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </button>
              <button @click="handleDelete(classItem.id, classItem.name)"
                class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-100/50">
                <Icon name="tabler:trash" class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-end">
      <div class="flex items-center text-sm text-gray-600 space-x-2">
        <button
          class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 disabled:opacity-50 transition-colors"
          disabled>
          <Icon name="tabler:chevron-left" class="w-4 h-4" />
        </button>
        <span class="px-2 py-1">Halaman 1 dari 2</span>
        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
          <Icon name="tabler:chevron-right" class="w-4 h-4" />
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
// ==============================================
// 1. DATA SIMULASI (Diganti dengan fetch API sesungguhnya)
// ==============================================
const rawClassData = ref([
  { "id": "0f1babaf-5f23-4ad8-aa0c-de3981c38326", "name": "XII DKV 1", "description": "Kelas XII Jurusan DKV Rombel 1", "homeroom_teacher": 'Siti Rahmawati', "created_at": "2025-12-06T14:45:31.000000Z", "updated_at": "2025-12-06T14:45:31.000000Z" },
  { "id": "19004ebe-fcb0-4e91-8a95-f03720bc86b9", "name": "XI ANM 1", "description": "Kelas XI Jurusan ANM Rombel 1", "homeroom_teacher": 'Budi Santoso', "created_at": "2025-12-06T14:45:31.000000Z", "updated_at": "2025-12-06T14:45:31.000000Z" },
  { "id": "22475df6-aeab-4a2e-86fd-cadd382d1613", "name": "X RPL 1", "description": "Kelas X Jurusan RPL Rombel 1", "homeroom_teacher": null, "created_at": "2025-12-06T14:45:31.000000Z", "updated_at": "2025-12-06T14:45:31.000000Z" },
  { "id": "4ceb0b56-d2c0-44f4-a543-94d333ca9708", "name": "X DKV 1", "description": "Kelas X Jurusan DKV Rombel 1", "homeroom_teacher": 'Joko Widodo', "created_at": "2025-12-06T14:45:31.000000Z", "updated_at": "2025-12-06T14:45:31.000000Z" },
  { "id": "59e70af0-95ee-4e26-90a7-ebd34afda339", "name": "XII RPL 2", "description": "Kelas XII Jurusan RPL Rombel 2", "homeroom_teacher": null, "created_at": "2025-12-06T14:45:31.000000Z", "updated_at": "2025-12-06T14:45:31.000000Z" },
]);

// ==============================================
// 2. LOGIKA FILTER
// ==============================================
const isFilterVisible = ref(false);

const filterForm = reactive({
  search: '',
  level: '', // X, XI, atau XII
});

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const applyFilter = () => {
  console.log(`Menerapkan filter: Cari='${filterForm.search}', Level='${filterForm.level}'`);
  // Di sini Anda akan melakukan pemanggilan API untuk mengambil data berdasarkan filterForm.value
  alert('Filter diterapkan! (Cek console log untuk detail filter)');
};

// Computed property untuk menampilkan data setelah filter di frontend (untuk simulasi)
const filteredClasses = computed(() => {
  let data = rawClassData.value;

  if (filterForm.search) {
    const searchLower = filterForm.search.toLowerCase();
    data = data.filter(item =>
      item.name.toLowerCase().includes(searchLower) ||
      item.description.toLowerCase().includes(searchLower)
    );
  }

  if (filterForm.level) {
    data = data.filter(item => item.name.startsWith(filterForm.level));
  }

  return data;
});

// ==============================================
// 3. LOGIKA AKSI (CRUD)
// ==============================================
const handleCreate = () => {
  alert('Navigasi ke halaman Tambah Data Kelas');
  // Contoh: navigateTo('/classes/create');
};

const handleEdit = (id) => {
  alert(`Navigasi ke halaman Edit Kelas ID: ${id}`);
  // Contoh: navigateTo(`/classes/${id}/edit`);
};

const handleDelete = (id, name) => {
  if (confirm(`Apakah Anda yakin ingin menghapus kelas: ${name}?`)) {
    alert(`Permintaan HAPUS berhasil dikirim untuk ID: ${id}`);
    // Di dunia nyata: Panggil API delete dan muat ulang data.
  }
};
</script>