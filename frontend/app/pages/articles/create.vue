<template>
  <div class="space-y-4">

    <div class="flex items-center">
      <Icon name="tabler:notebook" class="w-8 h-8 mr-2 text-primary-600" />
      <h1 class="text-3xl font-bold text-gray-900">
        Buat Artikel Baru
      </h1>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">

      <form @submit.prevent="submitArticle" class="space-y-8">

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
            <input type="text" id="slug" v-model="form.slug"
              class="form-input border-gray-300 bg-gray-50 disabled:bg-gray-100" required :disabled="isSubmitting"
              readonly :class="{ 'border-red-500': errors.slug }" />
            <p v-if="errors.slug" class="mt-1 text-xs text-red-500">{{ errors.slug[0] }}</p>
            <small class="text-gray-500 mt-1 block">Pastikan slug unik dan hanya berisi huruf, angka, dan strip
              (-).</small>
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
              <input type="text" id="thumbnail_file_id" v-model="form.thumbnail_file_id"
                placeholder="ID File Gambar (Mis: 123)" class="form-input" :disabled="isSubmitting"
                :class="{ 'border-red-500': errors.thumbnail_file_id }" />
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
            <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin segera dipublikasikan (tergantung status).</p>
            <p v-if="errors.published_at" class="mt-1 text-xs text-red-500">{{ errors.published_at[0] }}</p>
          </div>
        </section>

        <div class="flex justify-end space-x-3">
          <NuxtLink to="/articles"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting"
            class="px-4 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-md disabled:bg-primary-400">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Artikel
          </button>
        </div>

      </form>

    </div>

  </div>
</template>

<script setup lang="ts">
const router = useRouter();

interface ArticlePayload {
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

const categories = ref<{ id: string, name: string }[]>([]);

const generateSlug = () => {
  if (form.title && form.slug === slugify(form.slug)) {
    form.slug = slugify(form.title);
  }
};

async function fetchMasterData() {
  try {
    const res = await useApi().get<{ id: string, name: string }[]>('/master-data/article-categories');
    if (res.status && res.data) {
      categories.value = res.data;
    } else {
      useToast().error('Gagal memuat daftar kategori.');
    }
  } catch (e) {
    useToast().error('Error saat mengambil data kategori.');
  }
}

const submitArticle = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  try {
    const response = await useApi().post('/admin/articles', form);

    if (response.status) {
      useToast().success('Artikel berhasil dibuat dan disimpan!');
      router.push('/articles');
    } else {
      useToast().error(response.message || 'Gagal menyimpan artikel.');
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
  fetchMasterData();
});

</script>