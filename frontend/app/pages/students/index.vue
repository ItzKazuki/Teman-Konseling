<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">

      <h3 class="text-2xl font-semibold text-gray-800">Daftar Siswa</h3>

      <div class="flex items-center space-x-3">

        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <NuxtLink to="/students/create" v-if="can(['bk', 'staff'])"
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
        <input type="text" v-model="filters.search" placeholder="Cari Nama, NIS, atau NISN"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        <FormSelect name="filter-class" v-model="filters.classId" :options="[
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

          <tr v-if="data.length === 0">
            <td colspan="5" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">

              <div class="flex items-center justify-center">
                <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
                <span>Tidak ada data siswa yang ditemukan.</span>
              </div>
            </td>
          </tr>

          <tr v-for="student in data" :key="student.id" class="hover:bg-primary-50/50 transition-colors duration-100">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center gap-3">
                <div class="relative inline-flex">
                  <img :src="student.avatar_url || `https://ui-avatars.com/api/?name=${student.name}&background=random`"
                    class="w-10 h-10 rounded-xl object-cover border border-gray-100 shadow-sm" :alt="student.name" />
                  <span :class="[
                    'absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white',
                    student.is_online ? 'bg-emerald-500' : 'bg-gray-300'
                  ]" :title="student.is_online ? 'Online' : 'Offline'"></span>
                </div>
                <div>
                  <div class="text-sm font-bold text-gray-900 leading-none mb-1">{{ student.name }}</div>
                  <div class="text-[11px] text-gray-500 flex items-center gap-1">
                    <Icon name="tabler:mail" class="w-3.5 h-3.5" />
                    {{ student.email }}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
              <div class="flex items-center gap-1.5">
                <Icon name="tabler:door-enter" class="w-4 h-4 text-gray-400" />
                {{ student.classroom_name ?? '-' }}
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex flex-col gap-1">
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 w-fit">
                  NIS: {{ student.nis }}
                </span>
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary-50 text-primary-600 w-fit">
                  NISN: {{ student.nisn }}
                </span>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ student.email }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
              <div class="flex items-center justify-center gap-1">
                <NuxtLink :to="`/students/detail/${student.id}`"
                  class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Detail Siswa">
                  <Icon name="tabler:eye" class="w-5 h-5" />
                </NuxtLink>

                <NuxtLink :to="`/students/${student.id}`" v-if="can(['bk', 'staff'])"
                  class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Edit Data">
                  <Icon name="tabler:edit" class="w-5 h-5" />
                </NuxtLink>

                <button @click="handleDelete(student.id ?? '', student.name)" v-if="can(['bk', 'staff'])"
                  class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Hapus Siswa">
                  <Icon name="tabler:trash" class="w-5 h-5" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <AppTablePagination :meta="meta" @change="changePage" v-if="data.length > 0" />

  </div>
</template>

<script setup lang="ts">
const { can } = usePermission();
const {
  data,
  loading,
  meta,
  filters,
  fetchData,
  changePage,
  applyFilter
} = useDataTable<Student, { search: string; classId: string }>('/students', {
  search: '',
  classId: ''
});

const classRooms = ref<MasterDataClassroom[]>([]);
const isFilterVisible = ref(false);

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

// 2. Fungsi hapus sekarang memanggil fetchData() milik composable untuk refresh
const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus siswa "${name}"?`);
    if (!confirmed) return;

    const res = await useApi().destroy(`/students/${id}`);

    if (res.status) {
      useToast().success(`Data siswa "${name}" berhasil dihapus.`);
      await fetchData(); // Refresh data pada halaman yang sama
    }
  } catch (err: any) {
    useToast().error(err?.data?.message || 'Terjadi kesalahan.');
  }
};

async function fetchClassRooms() {
  try {
    const res = await useApi().get<MasterDataClassroom[]>('/reference/classrooms');
    if (res.status && res.data) classRooms.value = res.data;
  } catch (e) {
    useToast().error('Error saat mengambil data kelas.');
  }
}

onMounted(() => {
  fetchClassRooms();
  fetchData(); // Ambil data awal via composable
});
</script>