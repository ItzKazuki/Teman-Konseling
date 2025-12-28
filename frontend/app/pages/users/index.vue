<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">

      <h3 class="text-2xl font-semibold text-gray-800">Daftar Pengguna Sistem</h3>

      <div class="flex items-center space-x-3">

        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <NuxtLink to="/users/create"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Pengguna
        </NuxtLink>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Pengguna</h4>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" v-model="filters.search" placeholder="Cari Nama, Email, atau NIP"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        <FormSelect name="filter-role" v-model="filters.role" :options="[
          { value: '', label: 'Semua Peran (Role)' },
          { value: 'guru', label: 'Guru' },
          { value: 'bk', label: 'BK/Konselor' },
          { value: 'admin', label: 'Administrator' },
        ]" />
        <button @click="applyFilter"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 md:col-span-3 lg:col-span-1">
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
              Status Ketersediaan
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
                <p>Tidak ada pengguna yang ditemukan.</p>
              </div>
            </td>
          </tr>

          <tr v-for="user in data" :key="user.id" class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
              <div class="text-xs text-gray-500">{{ user.email }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.jabatan }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span :class="getRoleClass(user.role)"
                class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full uppercase">
                {{ user.role }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span :class="user.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full uppercase">
                {{ user.is_available ? 'Tersedia' : 'Tidak Tersedia' }}
              </span>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <NuxtLink :to="`/users/${user.id}`"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </NuxtLink>
              <button @click="handleDelete(user.id ?? '', user.name)"
                class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-100/50">
                <Icon name="tabler:trash" class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <AppTablePagination :meta="meta" @change="changePage" v-if="data.length > 0" />

  </div>
</template>

<script setup lang="ts">
const {
  data,
  loading,
  meta,
  filters,
  fetchData,
  changePage,
  applyFilter
} = useDataTable<User, { search: string; role: string }>('/admin/users', {
  search: '',
  role: ''
});

const isFilterVisible = ref<boolean>(false);

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus pengguna "${name}"?`);

    if (!confirmed) return;

    const message = await useApi().destroy(`/admin/users/${id}`);

    if (message.status) {
      useToast().success(`Data pengguna "${name}" berhasil dihapus.`);
      await fetchData();
    } else {
      useToast().error('Gagal menghapus data pengguna. Silakan coba lagi.');
    }
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  }
};

onMounted(() => {
  fetchData();
});
</script>