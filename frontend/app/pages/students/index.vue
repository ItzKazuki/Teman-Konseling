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

        <NuxtLink to="/students/create"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Siswa
        </NuxtLink>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Siswa</h4>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" v-model="filterForm.search" placeholder="Cari Nama, NIS, atau NISN"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        <FormSelect name="filter-class" v-model="filterForm.classId" :options="[
          { value: '', label: 'Semua Kelas' },
          ...classRooms.map(c => ({ value: c.id, label: c.name }))
        ]" />

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
              {{ student.classroom_name ?? '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-mono text-gray-700">NIS: {{ student.nis }}</div>
              <div class="text-xs font-mono text-gray-500">NISN: {{ student.nisn }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ student.email }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <NuxtLink :to="`/students/${student.id}`"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </NuxtLink>
              <button @click="handleDelete(student.id ?? '', student.name)"
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

<script setup lang="ts">
const classRooms = ref<MasterDataClassroom[]>([]);

const rawStudentData = ref<Student[]>([]);

const getClassName = (classId: string) => {
  const classItem = classRooms.value.find(c => c.id === classId);
  return classItem ? classItem.name : 'Kelas Tidak Ditemukan';
};

const isFilterVisible = ref(false);

const filterForm = reactive({
  search: '',
  classId: '',
});

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const applyFilter = () => {
  alert('Filter diterapkan! (Cek console log untuk detail filter)');
};

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

const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus siswa "${name}"?`);

    if (!confirmed) return;

    const message = await useApi().destroy(`/admin/students/${id}`);

    if (message.status) {
      useToast().success(`Data siswa "${name}" berhasil dihapus.`);
      await fecthAllStudents();
    } else {
      useToast().error('Gagal menghapus data siswa. Silakan coba lagi.');
    }
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  }
};

async function fetchClassRooms() {
  try {
    const res = await useApi().get<MasterDataClassroom[]>('/master-data/classrooms');
    if (res.status && res.data) {
      classRooms.value = res.data;
    } else {
      useToast().error('Gagal memuat daftar ruang kelas.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data kelas.');
  }
}

async function fecthAllStudents() {
  try {
    const res = await useApi().get<Student[]>('/admin/students');
    if (res.status && res.data) {
      rawStudentData.value = res.data;
    } else {
      useToast().error('Gagal memuat daftar siswa.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data siswa.');
  }
}

onMounted(() => {
  fetchClassRooms();
  fecthAllStudents();
});
</script>