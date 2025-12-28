<template>
  <div class="space-y-6">

    <div class="flex items-center">
      <Icon name="tabler:edit" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Edit Artikel: {{ form.title || 'Memuat...' }}
      </h1>
    </div>

    <div v-if="isLoading" class="p-8 text-center text-gray-500 bg-white rounded-xl shadow-lg border border-gray-200">
      <Icon name="tabler:loader-2" class="w-8 h-8 animate-spin mx-auto text-primary-600" />
      <p class="mt-2">Memuat data artikel...</p>
    </div>

    <div v-else class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitArticleUpdate" class="space-y-8">

        <section class="space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Utama</h2>

          <div>
            <label for="title" class="form-label">Judul Artikel <span class="text-red-600">*</span></label>
            <input type="text" id="title" v-model="form.title" @input="generateSlug" class="form-input" required
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.title }" />
            <p v-if="errors.title" class="mt-1 text-xs text-red-500">{{ errors.title[0] }}</p>
          </div>

          <div>
            <label for="slug" class="form-label">Slug (URL) <span class="text-red-600">*</span></label>
            <input type="text" id="slug" v-model="form.slug" class="form-input bg-gray-50" required
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.slug }" />
            <p v-if="errors.slug" class="mt-1 text-xs text-red-500">{{ errors.slug[0] }}</p>
            <small class="text-gray-500 mt-1 block">Pastikan slug unik. Perubahan slug akan memengaruhi URL artikel yang
              sudah ada.</small>
          </div>

          <FormSelect name="article_category_id" label="Kategori Artikel" v-model="form.article_category_id"
            :options="categories.map(cat => ({ label: cat.name, value: cat.id }))" placeholder="Pilih Kategori"
            :disabled="isSubmitting || !categories.length" required />
        </section>

        <section class="space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Konten Artikel</h2>

          <div>
            <label for="excerpt" class="form-label">Ringkasan / Sinopsis (Maks. 255 Karakter)
              <span class="text-red-600">*</span></label>
            <textarea id="excerpt" v-model="form.excerpt" rows="3" maxlength="255" class="form-input" required
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.excerpt }"></textarea>
            <p v-if="errors.excerpt" class="mt-1 text-xs text-red-500">{{ errors.excerpt[0] }}</p>
          </div>

          <div>
            <label for="content" class="form-label">Uraian / Konten <span class="text-red-600">*</span></label>
            <TipTapEditor v-model="form.content" :disabled="isSubmitting" />
            <p v-if="errors.content" class="mt-1 text-xs text-red-500">{{ errors.content[0] }}</p>
          </div>
        </section>


        <section class="space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Pengaturan Publikasi</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
              <label for="thumbnail_file_id" class="form-label">Thumbnail Gambar</label>
              <input type="text" id="thumbnail_file_id" v-model="form.thumbnail_file_id" placeholder="ID File Gambar"
                class="form-input" :disabled="isSubmitting" :class="{ 'border-red-500': errors.thumbnail_file_id }" />
              <p class="mt-1 text-xs text-gray-500">Masukkan ID File dari File Manager.</p>
              <p v-if="errors.thumbnail_file_id" class="mt-1 text-xs text-red-500">{{ errors.thumbnail_file_id[0] }}</p>
            </div>

            <FormSelect name="status" label="Status Artikel" v-model="form.status" :options="[
              { label: 'Draft', value: 'draft' },
              { label: 'Published', value: 'published' },
              { label: 'Archived', value: 'archived' },
            ]" required :disabled="isSubmitting" />

          </div>

          <div>
            <label for="published_at" class="form-label">Jadwal Publikasi (Opsional)</label>
            <input type="datetime-local" id="published_at" v-model="form.published_at" class="form-input"
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.published_at }" />
            <p class="mt-1 text-xs text-gray-500">Atur tanggal dan waktu spesifik untuk publikasi.</p>
            <p v-if="errors.published_at" class="mt-1 text-xs text-red-500">{{ errors.published_at[0] }}</p>
          </div>
        </section>

        <div class="flex justify-end space-x-3">
          <NuxtLink to="/admin/articles"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Kembali ke Daftar
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting || isLoading"
            class="px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan
          </button>
        </div>

      </form>

    </div>

  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();

const articleId = route.params.id as string;
if (!articleId) {
  router.replace('/articles');
}

interface ArticlePayload {
  author_id: string;
  article_category_id: string;
  title: string;
  slug: string;
  excerpt: string;
  thumbnail_file_id: string;
  content: string;
  status: 'draft' | 'published' | 'archived';
  published_at: string | null;
}

const initialForm: ArticlePayload = {
  author_id: '',
  article_category_id: '',
  title: '',
  slug: '',
  excerpt: '',
  thumbnail_file_id: '',
  content: '',
  status: 'draft',
  published_at: null,
};

const form = reactive<ArticlePayload>({ ...initialForm });
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);
const isLoading = ref(true);

const categories = ref<{ id: string, name: string }[]>([]);

const generateSlug = () => {
  form.slug = slugify(form.title);
};

function formatDateTimeForInput(isoDate: string | null): string | null {
  if (!isoDate) return null;

  try {
    const date = new Date(isoDate);
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    const hh = String(date.getHours()).padStart(2, '0');
    const min = String(date.getMinutes()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd}T${hh}:${min}`;
  } catch (e) {
    return null;
  }
}

async function fetchInitialData() {
  isLoading.value = true;
  try {
    const resCat = await useApi().get<{ id: string, name: string }[]>('/master-data/article-categories');
    if (resCat.status && resCat.data) {
      categories.value = resCat.data;
    } else {
      useToast().error('Gagal memuat daftar kategori.');
    }

    const resArticle = await useApi().get<ArticlePayload>(`/admin/articles/${articleId}`);
    if (resArticle.status && resArticle.data) {
      const data = resArticle.data;

      Object.assign(form, {
        ...data,
        published_at: formatDateTimeForInput(data.published_at),
        author_id: data.author_id,
      });
    } else {
      useToast().error('Data artikel tidak ditemukan atau gagal dimuat.');
      router.replace('/articles');
    }

  } catch (e) {
    useToast().error('Terjadi kesalahan saat memuat data awal.');
    router.replace('/articles');
  } finally {
    isLoading.value = false;
  }
}

const submitArticleUpdate = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined);

  try {
    const response = await useApi().put(`/admin/articles/${articleId}`, form);

    if (response.status) {
      useToast().success('Artikel berhasil diperbarui!');
      router.push('/articles');
    } else {
      useToast().error(response.message || 'Gagal menyimpan perubahan.');
    }

  } catch (err: any) {
    if (err?.data?.errors) {
      Object.assign(errors, err.data.errors);
      useToast().error('Validasi gagal. Silakan periksa kembali isian Anda.');
    } else {
      useToast().error(err.data?.message || 'Terjadi kesalahan jaringan atau server.');
    }
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchInitialData();
});
</script>