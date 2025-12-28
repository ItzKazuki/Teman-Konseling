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

          <div class="grid grid-cols-1 gap-6">
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
                      <p class="text-sm text-gray-500 font-medium">Klik untuk pilih gambar</p>
                      <p class="text-xs text-gray-400 mt-1">PNG, JPG atau WEBP (Maks. 2MB)</p>
                    </div>
                    <input id="thumbnail-upload" type="file" class="hidden" accept="image/*"
                      @change="handleThumbnailChange" />
                  </label>
                </div>
              </div>

              <p v-if="errors.thumbnail_file_id" class="mt-1 text-xs text-red-500">{{ errors.thumbnail_file_id[0] }}</p>
            </div>
          </div>

          <div>
            <label for="published_at" class="form-label">Jadwal Publikasi (Opsional)</label>
            <input type="datetime-local" id="published_at" v-model="form.published_at" class="form-input"
              :disabled="isSubmitting" :class="{ 'border-red-500': errors.published_at }" />
          </div>

          <ModalCropper :show="showCropper" :src="imageSource" :aspect-ratio="16 / 9" @close="showCropper = false"
            @cropped="onThumbnailCropped" />
        </section>

        <div class="flex justify-end space-x-3">
          <NuxtLink to="/articles"
            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50">
            Batal
          </NuxtLink>
          <button type="button" @click="saveAsDraft"
            class="px-4 py-2 text-sm font-medium bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors shadow-md"
            :disabled="isSubmitting">
            Draft
          </button>
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
  status: 'published',
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

// file

const thumbnailFile = ref<File | null>(null);
const thumbnailPreview = ref<string | null>(null);
const imageSource = ref<any>(null);
const showCropper = ref(false);

const handleThumbnailChange = (event: any) => {
  const file = event.target.files?.[0];
  if (!file) return;

  // Cek ukuran file (2MB = 2 * 1024 * 1024 bytes)
  if (file.size > 2 * 1024 * 1024) {
    useToast().error('Ukuran file terlalu besar. Maksimal 2MB.');
    event.target.value = ''; // Reset input
    return;
  }

  const reader = new FileReader();
  reader.onload = () => {
    imageSource.value = reader.result;
    showCropper.value = true; // Buka cropper
  };
  reader.readAsDataURL(file);
};

const onThumbnailCropped = (base64: string) => {
  thumbnailPreview.value = base64; // Untuk tampilan preview di frontend
  thumbnailFile.value = base64ToFile(base64, "thumbnail.jpeg");
  showCropper.value = false;
};

const removeThumbnail = () => {
  thumbnailFile.value = null;
  thumbnailPreview.value = null;
  form.thumbnail_file_id = '';
};

// end file

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

const saveAsDraft = async () => {
  form.status = 'draft';

  const confirmed = await useAlert().confirm('Apakah Anda yakin ingin menyimpan artikel ini sebagai draft?');

  if (!confirmed) {
    isSubmitting.value = false;
    return;
  }

  await submitArticle();
};

const submitArticle = async () => {
  isSubmitting.value = true;
  Object.keys(errors).forEach(key => errors[key] = undefined); // Reset errors

  if (form.status !== 'draft') {
    const confirm = await useAlert().confirm('Apakah Anda yakin ingin menyimpan artikel ini?');

    if (!confirm) {
      isSubmitting.value = false;
      return;
    }
  }

  try {

    if (thumbnailFile.value) {
      const fileId = await useFile().uploadAsset(thumbnailFile.value, 'thumbnails', 'public');
      if (!fileId) throw new Error('Gagal mengunggah thumbnail');

      form.thumbnail_file_id = fileId;
    }

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