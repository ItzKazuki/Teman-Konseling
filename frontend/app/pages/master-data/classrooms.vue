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
        <input type="text" v-model="filters.search" placeholder="Cari berdasarkan Nama atau Deskripsi"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full sm:w-auto focus:ring-primary-500 focus:border-primary-500" />
        <select v-model="filters.level"
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
              Level Kelas
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

          <tr v-if="data.length === 0">
            <td colspan="5" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <div class="flex justify-center items-center">
                <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
                <p>Tidak ada data kelas yang ditemukan.</p>
              </div>
            </td>
          </tr>

          <tr v-for="classItem in data" :key="classItem.id"
            class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ classItem.name }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.level }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.description }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.homeroom_teacher_name || '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
              <button @click="handleEdit(classItem.id ?? '')"
                class="text-primary-600 hover:text-primary-800 transition-colors p-1 rounded hover:bg-primary-100/50">
                <Icon name="tabler:edit" class="w-4 h-4" />
              </button>
              <button @click="handleDelete(classItem.id ?? '', classItem.name)"
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

  <Modal :show="showModal" :title="modalTitle" max-width="xl" @close="closeModal">
    <form @submit.prevent="submitForm" class="space-y-6">

      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas <span
            class="text-red-500">*</span></label>
        <input type="text" id="name" v-model="form.name"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full focus:ring-primary-500 focus:border-primary-500"
          required :disabled="isSubmitting" />
        <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name }}</p>
      </div>

      <FormSelect name="homeroom_teacher" label="Wali Kelas" v-model="form.homeroom_teacher"
        :options="teachers.map(teacher => ({ label: teacher.name, value: teacher.id }))" placeholder="Pilih Wali Kelas"
        :disabled="isSubmitting || !teachers.length" required />

      <FormSelect name="level" label="Level Kelas" v-model="form.level"
        :options="[{ label: '10', value: 10 }, { label: '11', value: 11 }, { label: '12', value: 12 }]"
        placeholder="Pilih Level Kelas" :disabled="isSubmitting" required />

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea id="description" v-model="form.description" rows="3"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full focus:ring-primary-500 focus:border-primary-500"
          :disabled="isSubmitting"></textarea>
        <p v-if="errors.description" class="mt-1 text-xs text-red-500">{{ errors.description }}</p>
      </div>

      <div class="pt-4 border-t flex justify-end">
        <button type="button" @click="closeModal" :disabled="isSubmitting"
          class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 mr-3">
          Batal
        </button>

        <button type="submit" :disabled="isSubmitting"
          class="px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
          <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
          {{ isEditMode ? 'Simpan Perubahan' : 'Buat Kelas' }}
        </button>
      </div>
    </form>
  </Modal>
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
} = useDataTable<Classroom, { search: string; level: string }>('/master-data/classrooms', {
  search: '',
  level: ''
});

const teachers = ref<Teacher[]>([]);
const isFilterVisible = ref<boolean>(false);
const isLoading = ref<boolean>(true);

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const showModal = ref<boolean>(false);
const initialForm: Classroom = { name: '', homeroom_teacher: '', description: '', level: 10 };
const form = reactive<Classroom>({ ...initialForm });
const errors = reactive<{ [key: string]: string | undefined }>({});
const isSubmitting = ref(false);
const currentEditId = ref<string | null>(null); // Menyimpan ID saat ini yang diedit

const isEditMode = computed(() => !!currentEditId.value);
const modalTitle = computed(() => isEditMode.value ? 'Ubah Kelas' : 'Tambah Kelas');

const closeModal = () => {
  showModal.value = false;
  Object.keys(errors).forEach(key => errors[key] = undefined);
  Object.assign(form, initialForm);
  currentEditId.value = null;
};

const handleCreate = () => {
  currentEditId.value = null;
  Object.assign(form, initialForm); // Pastikan form bersih
  showModal.value = true;
};

const handleEdit = (id: string) => {
  currentEditId.value = id; // Set ID yang akan diedit
  showModal.value = true;
};

async function fetchCategoryData(id: string) {
  isSubmitting.value = true;
  try {
    const response = await useApi().get<Classroom>(`/master-data/classrooms/${id}`);
    if (response.status && response.data) {
      Object.assign(form, {
        name: response.data.name,
        homeroom_teacher: response.data.homeroom_teacher || '',
        description: response.data.description || '',
      });
    } else {
      useToast().error('Gagal memuat data kategori.');
      closeModal();
    }
  } catch (err: any) {
    useToast().error('Terjadi kesalahan saat memuat data.');
    closeModal();
  } finally {
    isSubmitting.value = false;
  }
}

const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus Kelas "${name}"?`);

    if (!confirmed) return;

    const message = await useApi().destroy(`/master-data/classrooms/${id}`);

    if (message.status) {
      useToast().success(`Kelas "${name}" berhasil dihapus.`);
      await fetchData(); // Refresh data setelah penghapusan
    } else {
      useToast().error('Gagal menghapus Kelas. Silakan coba lagi.');
    }
  } catch (err: any) {
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  }
};

const submitForm = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    let response;
    let successMessage: string;

    if (isEditMode.value && currentEditId.value) {
      response = await useApi().put(`/master-data/classrooms/${currentEditId.value}`, form);
      successMessage = 'Kelas berhasil diperbarui!';
    } else {
      response = await useApi().post(`/master-data/classrooms`, form);
      successMessage = 'Kelas berhasil dibuat!';
    }

    if (response.status) {
      useToast().success(successMessage);
      await fetchData(); // Refresh data tabel
      closeModal();
    } else {
      if (response.errors) {
        Object.assign(errors, response.errors);
      } else {
        useToast().error(response.message || 'Gagal menyimpan data. Silakan cek form.');
      }
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan.');
    }
  } finally {
    isSubmitting.value = false;
  }
};

watch(showModal, (newVal) => {
  if (newVal && currentEditId.value) {
    fetchCategoryData(currentEditId.value);
  }
});

async function getAllTeachers() {
  isLoading.value = true;
  const resTeachers = await useApi().get<Teacher[]>('/reference/teachers');
  if (resTeachers.status && resTeachers.data) {
    teachers.value = resTeachers.data;
  } else {
    teachers.value = [];
  }
  isLoading.value = false;
}

onMounted(() => {
  fetchData();
  getAllTeachers();
})
</script>