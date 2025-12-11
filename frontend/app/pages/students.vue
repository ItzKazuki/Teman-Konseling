<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">

      <h3 class="text-2xl font-semibold text-gray-800">Daftar Siswa Aktif</h3>

      <div class="flex items-center space-x-3">

        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <button @click="handleCreate"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Siswa
        </button>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Siswa</h4>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" v-model="filterForm.search" placeholder="Cari Nama, NIS, atau NISN"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        <select v-model="filterForm.classId"
          class="form-select rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
          <option value="">Semua Kelas</option>
          <option v-for="c in classRooms" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>

        <button @click="applyFilter"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 w-full">
          Terapkan Filter
        </button>
      </div>
    </div>


    <div class="overflow-x-auto border border-gray-200 rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nama Siswa
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Kelas
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              NIS / NISN
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">

          <tr v-if="filteredStudents.length === 0">
            <td colspan="5" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
              Tidak ada data siswa yang ditemukan.
            </td>
          </tr>

          <tr v-for="student in filteredStudents" :key="student.id"
            class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ student.name }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ getClassName(student.class_room_id) }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-mono text-gray-700">NIS: {{ student.nis }}</div>
              <div class="text-xs font-mono text-gray-500">NISN: {{ student.nisn }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ student.email }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <button @click="handleEdit(student.id)"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </button>
              <button @click="handleDelete(student.id, student.name)"
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

// Data Dummy untuk Mapping Class ID ke Nama Kelas
const classRooms = ref([
  { id: "88a02e4d-d310-403d-8e20-6e81a0fe8e2e", name: "XI RPL 2" },
  { id: "22475df6-aeab-4a2e-86fd-cadd382d1613", name: "X RPL 1" },
  { id: "5ea17ea0-f7ae-429f-8858-331ad1c63983", name: "XI DKV 1" },
  // Tambahkan data kelas lain yang relevan di sini
]);

const rawStudentData = ref([
  {
    "id": "019af42d-9860-712a-8f85-d0e91a24d123",
    "nis": "0793",
    "nisn": "0077902757",
    "name": "Chaeza Ibnu Akbar",
    "class_room_id": "88a02e4d-d310-403d-8e20-6e81a0fe8e2e",
    "email": "cha@example.com",
    "created_at": "2025-12-06T15:00:20.000000Z",
    "updated_at": "2025-12-06T15:00:20.000000Z"
  },
  {
    "id": "019af42d-9860-712a-8f85-d0e91a24d124",
    "nis": "0801",
    "nisn": "0088123456",
    "name": "Budi Santoso",
    "class_room_id": "22475df6-aeab-4a2e-86fd-cadd382d1613",
    "email": "budi@example.com",
    "created_at": "2025-12-06T15:00:20.000000Z",
    "updated_at": "2025-12-06T15:00:20.000000Z"
  },
  {
    "id": "019af42d-9860-712a-8f85-d0e91a24d125",
    "nis": "0810",
    "nisn": "0089876543",
    "name": "Siti Aminah",
    "class_room_id": "5ea17ea0-f7ae-429f-8858-331ad1c63983",
    "email": "siti@example.com",
    "created_at": "2025-12-06T15:00:20.000000Z",
    "updated_at": "2025-12-06T15:00:20.000000Z"
  }
]);

// Fungsi untuk mendapatkan nama kelas dari ID
const getClassName = (classId) => {
  const classItem = classRooms.value.find(c => c.id === classId);
  return classItem ? classItem.name : 'Kelas Tidak Ditemukan';
};

// ==============================================
// 2. LOGIKA FILTER
// ==============================================
const isFilterVisible = ref(false);

const filterForm = reactive({
  search: '',
  classId: '', // ID Kelas untuk filter
});

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const applyFilter = () => {
  console.log(`Menerapkan filter: Cari='${filterForm.search}', Class ID='${filterForm.classId}'`);
  alert('Filter diterapkan! (Cek console log untuk detail filter)');
};

// Computed property untuk menampilkan data setelah filter di frontend (untuk simulasi)
const filteredStudents = computed(() => {
  let data = rawStudentData.value;

  if (filterForm.search) {
    const searchLower = filterForm.search.toLowerCase();
    data = data.filter(item =>
      item.name.toLowerCase().includes(searchLower) ||
      item.email.toLowerCase().includes(searchLower) ||
      item.nis.includes(filterForm.search) ||
      item.nisn.includes(filterForm.search)
    );
  }

  if (filterForm.classId) {
    data = data.filter(item => item.class_room_id === filterForm.classId);
  }

  return data;
});

// ==============================================
// 3. LOGIKA AKSI (CRUD)
// ==============================================
const handleCreate = () => {
  alert('Navigasi ke halaman Tambah Data Siswa Baru');
  // Contoh: navigateTo('/students/create');
};

const handleEdit = (id) => {
  alert(`Navigasi ke halaman Edit Siswa ID: ${id}`);
  // Contoh: navigateTo(`/students/${id}/edit`);
};

const handleDelete = (id, name) => {
  if (confirm(`Apakah Anda yakin ingin menghapus data siswa: ${name}?`)) {
    alert(`Permintaan HAPUS berhasil dikirim untuk ID: ${id}`);
    // Di dunia nyata: Panggil API delete dan muat ulang data.
  }
};
</script>