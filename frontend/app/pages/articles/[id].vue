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
          </div>

          <FormSelect name="article_category_id" label="Kategori Artikel" v-model="form.article_category_id"
            :options="categories.map(cat => ({ label: cat.name, value: cat.id }))" placeholder="Pilih Kategori"
            :disabled="isSubmitting || !categories.length" required />
        </section>

        <section class="space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Konten Artikel</h2>
          <div>
            <label for="excerpt" class="form-label">Ringkasan / Sinopsis <span class="text-red-600">*</span></label>
            <textarea id="excerpt" v-model="form.excerpt" rows="3" maxlength="255" class="form-input" required
              :disabled="isSubmitting"></textarea>
          </div>

          <div>
            <label for="content" class="form-label">Uraian / Konten <span class="text-red-600">*</span></label>
            <TipTapEditor v-model="form.content" :disabled="isSubmitting" />
          </div>
        </section>

        <section class="space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Pengaturan Publikasi</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="form-label">Thumbnail Artikel</label>
              <div class="mt-2 flex flex-col items-start gap-4">
                <div v-if="thumbnailPreview" class="relative group">
                  <img :src="thumbnailPreview"
                    class="w-full max-w-sm aspect-video object-cover rounded-xl border-2 border-primary-100 shadow-sm" />
                  <button type="button" @click="removeThumbnail"
                    class="absolute top-2 flex items-center right-2 p-1.5 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                    <Icon name="tabler:x" class="w-4 h-4" />
                  </button>
                </div>

                <div v-else class="w-full max-w-sm">
                  <label for="thumbnail-upload"
                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <Icon name="tabler:photo-plus" class="w-10 h-10 text-gray-400 mb-2" />
                      <p class="text-sm text-gray-500 font-medium">Klik untuk ganti gambar</p>
                    </div>
                    <input id="thumbnail-upload" type="file" class="hidden" accept="image/*" @change="handleThumbnailChange" />
                  </label>
                </div>
              </div>
            </div>

            <FormSelect name="status" label="Status Artikel" v-model="form.status" :options="[
              { label: 'Published', value: 'published' },
              { label: 'Draft', value: 'draft' },
              { label: 'Archived', value: 'archived' },
            ]" required :disabled="isSubmitting" />
          </div>

          <div>
            <label for="published_at" class="form-label">Jadwal Publikasi (Opsional)</label>
            <input type="datetime-local" id="published_at" v-model="form.published_at" class="form-input"
              :disabled="isSubmitting" />
          </div>
        </section>

        <div class="flex justify-end space-x-3">
          <NuxtLink to="/articles" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg">
            Batal
          </NuxtLink>
          <button type="submit" :disabled="isSubmitting || isLoading" class="px-6 py-2 bg-primary-600 text-white rounded-lg shadow-md hover:bg-primary-700">
            <Icon v-if="isSubmitting" name="tabler:loader-2" class="w-4 h-4 mr-1 animate-spin" />
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

    <ModalCropper :show="showCropper" :src="imageSource" :aspect-ratio="16 / 9" @close="showCropper = false"
      @cropped="onThumbnailCropped" />
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const router = useRouter();
const articleId = route.params.id as string;

// Form State
interface ArticlePayload {
  article_category_id: string;
  title: string;
  slug: string;
  excerpt: string;
  thumbnail_file_id: string;
  content: string;
  status: 'published' | 'draft' | 'archived';
  published_at: string | null;
}

const form = reactive<ArticlePayload>({
  article_category_id: '',
  title: '',
  slug: '',
  excerpt: '',
  thumbnail_file_id: '',
  content: '',
  status: 'published',
  published_at: null,
});

// UI State
const errors = reactive<{ [key: string]: string[] | undefined }>({});
const isSubmitting = ref(false);
const isLoading = ref(true);
const categories = ref<{ id: string, name: string }[]>([]);

// File State
const thumbnailFile = ref<File | null>(null);
const thumbnailPreview = ref<string | null>(null);
const imageSource = ref<any>(null);
const showCropper = ref(false);

// Methods
const generateSlug = () => { form.slug = slugify(form.title); };

const handleThumbnailChange = (event: any) => {
  const file = event.target.files?.[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = () => { imageSource.value = reader.result; showCropper.value = true; };
  reader.readAsDataURL(file);
};

const onThumbnailCropped = (base64: string) => {
  thumbnailPreview.value = base64;
  thumbnailFile.value = base64ToFile(base64, "thumbnail.jpeg");
  showCropper.value = false;
};

const removeThumbnail = () => {
  thumbnailFile.value = null;
  thumbnailPreview.value = null;
  form.thumbnail_file_id = '';
};

async function fetchInitialData() {
  isLoading.value = true;
  try {
    const [resCat, resArticle] = await Promise.all([
      useApi().get<{ id: string, name: string }[]>('/master-data/article-categories'),
      useApi().get<any>(`/admin/articles/${articleId}`)
    ]);

    if (resCat.status) categories.value = resCat.data;

    if (resArticle.status) {
      const data = resArticle.data;
      Object.assign(form, {
        article_category_id: data.article_category_id,
        title: data.title,
        slug: data.slug,
        excerpt: data.excerpt,
        thumbnail_file_id: data.thumbnail_file_id,
        content: data.content,
        status: data.status,
        published_at: formatDateTimeForInput(data.published_at),
      });
      // Set preview gambar dari URL yang ada di database
      if (data.thumbnail_url) thumbnailPreview.value = data.thumbnail_url;
    }
  } catch (e) {
    useToast().error('Gagal memuat data.');
  } finally {
    isLoading.value = false;
  }
}

const submitArticleUpdate = async () => {
  const confirm = await useAlert().confirm('Simpan perubahan pada artikel ini?');
  if (!confirm) return;

  isSubmitting.value = true;
  try {
    // Upload gambar BARU jika ada
    if (thumbnailFile.value) {
      const fileId = await useFile().uploadAsset(thumbnailFile.value, 'thumbnails', 'public');
      if (fileId) form.thumbnail_file_id = fileId;
    }

    const response = await useApi().put(`/admin/articles/${articleId}`, form);
    if (response.status) {
      useToast().success('Artikel diperbarui!');
      router.push('/articles');
    }
  } catch (err: any) {
    if (err?.data?.errors) Object.assign(errors, err.data.errors);
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => { fetchInitialData(); });
</script>