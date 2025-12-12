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
        <input type="text" v-model="filterForm.search" placeholder="Cari berdasarkan Nama atau Deskripsi"
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

          <tr v-if="filteredCategories.length === 0">
            <td colspan="4" class="px-6 py-6 whitespace-nowrap text-center text-sm text-gray-500">
              <div class="flex justify-center items-center">
                <Icon name="tabler:info-circle" class="w-5 h-5 inline-block mr-1 text-yellow-500" />
                <p>Tidak ada data kategori yang ditemukan.</p>
              </div>
            </td>
          </tr>

          <tr v-for="classItem in filteredCategories" :key="classItem.id"
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

    <div class="mt-4 flex justify-end">
      </div>

  </div>

  <Modal :show="showModal" :title="modalTitle" max-width="xl" @close="closeModal">
    <form @submit.prevent="submitForm" class="space-y-6">

      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
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
// ==========================================================
// 1. STATE UNTUK TABLE DAN FILTER
// ==========================================================
const rawArticleCategories = ref<ArticleCategory[]>([]);
const isFilterVisible = ref<boolean>(false);
const isLoading = ref<boolean>(true);
const filterForm = reactive({ search: '' });

const handleFilterToggle = () => {
  isFilterVisible.value = !isFilterVisible.value;
};

const applyFilter = () => {
  // Lakukan aksi filter di sini jika ada API filtering
  // Saat ini hanya menggunakan filtering computed property
  useToast().info('Filter data diterapkan.');
};

const filteredCategories = computed(() => {
  let data = rawArticleCategories.value;
  if (filterForm.search) {
    const searchLower = filterForm.search.toLowerCase();
    data = data.filter(item =>
      item.name.toLowerCase().includes(searchLower) ||
      item.slug?.toLowerCase().includes(searchLower)
    );
  }
  return data;
});

// ==========================================================
// 3. STATE DAN LOGIKA MODAL FORM
// ==========================================================

const showModal = ref<boolean>(false);
const initialForm: ArticleCategory = { name: '', slug: '', description: '' };
const form = reactive<ArticleCategory>({ ...initialForm });
const errors = reactive<{ [key: string]: string | undefined }>({});
const isSubmitting = ref(false);
const currentEditId = ref<string | null>(null); // Menyimpan ID saat ini yang diedit

// Computed Properties untuk Modal
const isEditMode = computed(() => !!currentEditId.value);
const modalTitle = computed(() => isEditMode.value ? 'Ubah Kategori Artikel' : 'Tambah Kategori Artikel');

/**
 * Menutup modal dan me-reset state
 */
const closeModal = () => {
  showModal.value = false;
  // Clear error dan reset form
  Object.keys(errors).forEach(key => errors[key] = undefined);
  Object.assign(form, initialForm);
  currentEditId.value = null;
};

/**
 * Membuka modal dalam mode Create
 */
const handleCreate = () => {
  currentEditId.value = null;
  Object.assign(form, initialForm); // Pastikan form bersih
  showModal.value = true;
};

/**
 * Membuka modal dalam mode Edit
 */
const handleEdit = (id: string) => {
  currentEditId.value = id; // Set ID yang akan diedit
  showModal.value = true;
};

/**
 * Mengisi form dengan data dari server
 */
async function fetchCategoryData(id: string) {
  isSubmitting.value = true;
  try {
    // Asumsi useApi().get mengembalikan { status: boolean, data: T }
    const response = await useApi().get<ArticleCategory>(`/admin/master-data/article-categories/${id}`);
    if (response.status && response.data) {
      // Isi form dengan data yang diambil
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

/**
 * Menghasilkan slug secara real-time dari Nama Kategori
 */
const generateSlug = () => {
  if (!isEditMode.value) {
    // Hanya generate otomatis saat mode Create
    form.slug = slugify(form.name); 
  }
};

/**
 * Mengirim formulir untuk Create atau Update
 */
const submitForm = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    let response;
    let successMessage: string;
    
    if (isEditMode.value && currentEditId.value) {
      // MODE UPDATE (Menggunakan PUT)
      response = await useApi().put(`/admin/article-categories/${currentEditId.value}`, form);
      successMessage = 'Kategori artikel berhasil diperbarui!';
    } else {
      // MODE CREATE (Menggunakan POST)
      response = await useApi().post(`/admin/master-data/article-categories`, form);
      successMessage = 'Kategori artikel berhasil dibuat!';
    }
    
    if (response.status) {
      useToast().success(successMessage);
      await getAllCategories(); // Refresh data tabel
      closeModal();
    } else {
      // Handle server validation errors yang non-422
      if (response.errors) {
        Object.assign(errors, response.errors);
      } else {
        useToast().error(response.message || 'Gagal menyimpan data. Silakan cek form.');
      }
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      // Handle Laravel's validation error response structure (422)
      Object.assign(errors, err.data.errors);
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan.');
    }
  } finally {
    isSubmitting.value = false;
  }
};

// 💡 Watcher yang diperbaiki: Panggil fetchCategoryData hanya saat modal dibuka dalam mode Edit
watch(showModal, (newVal) => {
  if (newVal && currentEditId.value) {
    // Modal dibuka dalam mode Edit
    fetchCategoryData(currentEditId.value);
  } 
  // Jika newVal adalah true TAPI currentEditId adalah null,
  // maka itu mode Create, dan form sudah direset di handleCreate.
});

// ==========================================================
// 4. LOGIKA PENGHAPUSAN DAN PENGAMBILAN DATA
// ==========================================================

const handleDelete = async (id: string, name: string) => {
  try {
    const confirmed = await useAlert().confirm(`Apakah Anda yakin ingin menghapus kategori artikel "${name}"?`);

    if (!confirmed) return;

    // Asumsi useApi().destroy mengembalikan { status: boolean, message: string }
    const message = await useApi().destroy(`/admin/master-data/article-categories/${id}`);

    if (message.status) {
      useToast().success(`Kategori Artikel "${name}" berhasil dihapus.`);
      await getAllCategories(); // Refresh data setelah penghapusan
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

async function getAllCategories() {
  isLoading.value = true;
  // Asumsi useApi().get mengembalikan { status: boolean, data: T }
  const resArticleCategories = await useApi().get<ArticleCategory[]>('/admin/master-data/article-categories');
  if (resArticleCategories.status && resArticleCategories.data) {
    rawArticleCategories.value = resArticleCategories.data;
  } else {
    // console.error('Gagal mengambil data kategori', resArticleCategories); // Gunakan console.error/log
    rawArticleCategories.value = [];
  }
  isLoading.value = false;
}

onMounted(() => {
  getAllCategories();
})
</script>