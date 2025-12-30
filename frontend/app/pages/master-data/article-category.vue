<template>
  <div class="bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
      <h3 class="text-2xl font-semibold text-gray-800">Master Data: Kategori Artikel</h3>
      <div class="flex items-center space-x-3">
        <button @click="handleFilterToggle"
          class="flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
          <Icon name="tabler:filter" class="w-5 h-5 mr-1" />
          Filter Data
        </button>

        <button @click="handleCreate"
          class="flex items-center px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md">
          <Icon name="tabler:plus" class="w-5 h-5 mr-1" />
          Tambah Kategori
        </button>
      </div>
    </div>

    <div v-if="isFilterVisible"
      class="mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300">
      <h4 class="text-sm font-semibold mb-3 text-gray-700">Opsi Filter Lanjutan</h4>
      <div class="flex flex-col sm:flex-row gap-4 items-center">
        <input type="text" v-model="filters.search" placeholder="Cari berdasarkan Nama atau Deskripsi"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full sm:w-auto focus:ring-primary-500 focus:border-primary-500" />
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
              Nama Kategori
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Slug
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Deskripsi
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Aksi
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">

          <tr v-if="data.length === 0">
            <td colspan="4" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <div class="flex justify-center items-center">
                <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
                <p>Tidak ada data kategori yang ditemukan.</p>
              </div>
            </td>
          </tr>

          <tr v-for="classItem in data" :key="classItem.id"
            class="hover:bg-primary-50/50 transition-colors duration-100">

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ classItem.name }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.slug }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ classItem.description || '-' }}
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
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span
            class="text-red-500">*</span></label>
        <input type="text" id="name" v-model="form.name" @input="generateSlug"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full focus:ring-primary-500 focus:border-primary-500"
          required :disabled="isSubmitting" />
        <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name }}</p>
      </div>

      <div>
        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
        <input type="text" id="slug" v-model="form.slug"
          class="form-input rounded-lg text-sm border-gray-300 shadow-sm w-full bg-gray-50 disabled:bg-gray-100 focus:ring-primary-500 focus:border-primary-500"
          :disabled="isSubmitting" />
        <p v-if="errors.slug" class="mt-1 text-xs text-red-500">{{ errors.slug }}</p>
      </div>

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
          {{ isEditMode ? 'Simpan Perubahan' : 'Buat Kategori' }}
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
} = useDataTable<ArticleCategory, { search: string; }>('/master-data/article-categories', {
  search: '',
});

const isFilterVisible = ref<boolean>(false);

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const showModal = ref<boolean>(false);
const initialForm: ArticleCategory = { id: '', name: '', slug: '', description: '' };
const form = reactive<ArticleCategory>({ ...initialForm });
const errors = reactive<{ [key: string]: string | undefined }>({});
const isSubmitting = ref(false);
const currentEditId = ref<string | null>(null);

const isEditMode = computed(() => !!currentEditId.value);
const modalTitle = computed(() => isEditMode.value ? 'Ubah Kategori Artikel' : 'Tambah Kategori Artikel');

const closeModal = () => {
  showModal.value = false;
  Object.keys(errors).forEach(key => errors[key] = undefined);
  Object.assign(form, initialForm);
  currentEditId.value = null;
};

const handleCreate = () => {
  currentEditId.value = null;
  Object.assign(form, initialForm);
  showModal.value = true;
};

const handleEdit = (id: string) => {
  currentEditId.value = id;
  showModal.value = true;
};

async function fetchCategoryData(id: string) {
  isSubmitting.value = true;
  try {
    const response = await useApi().get<ArticleCategory>(`/master-data/article-categories/${id}`);
    if (response.status && response.data) {
      Object.assign(form, {
        name: response.data.name,
        slug: response.data.slug || '',
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

const generateSlug = () => {
  if (!isEditMode.value) {
    form.slug = slugify(form.name);
  }
};

const submitForm = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    let response;
    let successMessage: string;

    if (isEditMode.value && currentEditId.value) {
      response = await useApi().put(`/article-categories/${currentEditId.value}`, form);
      successMessage = 'Kategori artikel berhasil diperbarui!';
    } else {
      response = await useApi().post(`/master-data/article-categories`, form);
      successMessage = 'Kategori artikel berhasil dibuat!';
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

const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus kategori artikel "${name}"?`);

    if (!confirmed) return;

    const message = await useApi().destroy(`/master-data/article-categories/${id}`);

    if (message.status) {
      useToast().success(`Kategori Artikel "${name}" berhasil dihapus.`);
      await fetchData(); // Refresh data setelah penghapusan
    } else {
      useToast().error('Gagal menghapus kategori artikel. Silakan coba lagi.');
    }
  } catch (err: any) {
    // ... (Error handling)
    if (err?.data?.message) {
      useToast().error(err.data.message);
    } else {
      useToast().error('Terjadi kesalahan. Silakan coba lagi.')
    }
  }
};

onMounted(() => {
  fetchData();
})
</script>