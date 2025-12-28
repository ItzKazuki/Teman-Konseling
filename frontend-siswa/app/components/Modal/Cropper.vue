<template>
  <div v-if="show" class="fixed inset-0 z-300 flex items-center justify-center bg-black/50 p-4">
    <!-- Background -->
    <div class="absolute inset-0" @click="emit('close')"></div>

    <!-- Modal -->
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-5 z-10">
      <div class="text-lg font-semibold mb-4">Crop Image</div>

      <div class="w-full max-h-[400px] overflow-hidden">
        <img ref="imageRef" :src="src" class="max-w-full" />
      </div>

      <div class="flex justify-end gap-3 mt-5">
        <button type="button" class="px-4 py-2 bg-gray-200 rounded-md" @click="emit('close')">
          Cancel
        </button>

        <button type="button" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-500"
          @click="confirm">
          Crop
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from "vue";
import Cropper from "cropperjs";
import 'cropperjs/dist/cropper.css';

const props = defineProps({
  show: Boolean,
  src: String,
  aspectRatio: {
    type: Number,
    default: null,
  },
});

const emit = defineEmits(["close", "cropped"]);

const imageRef = ref(null);
let cropper = null;

const initCropper = () => {
  if (!imageRef.value) return;

  if (cropper) {
    cropper.destroy();
    cropper = null;
  }

  cropper = new Cropper(imageRef.value, {
    // Gunakan nilai dari props
    aspectRatio: props.aspectRatio, 
    viewMode: 1,
    autoCropArea: 0.8,
    movable: true,
    zoomable: true,
    scalable: true,
    rotatable: false,
    cropBoxMovable: true,
    cropBoxResizable: true,
  });
};

const confirm = () => {
  if (!cropper) return;

  const canvas = cropper.getCroppedCanvas();

  const base64 = canvas.toDataURL("image/png");
  emit("cropped", base64);
  emit("close");
};

watch(
  () => props.show,
  async (val) => {
    if (val) {
      await nextTick();
      if (!imageRef.value) return;

      imageRef.value.onload = () => initCropper();

      if (imageRef.value.complete) initCropper();
    } else {
      if (cropper) cropper.destroy();
    }
  }
);
</script>
