<template>
  <div class="space-y-1">
    <label v-if="label" :for="name" class="form-label">
      {{ label }} <span v-if="required" class="text-red-600">*</span>
    </label>

    <div class="relative">
      <select :name="name" :id="name" v-model="modelValueLocal" :required="required && !allowPlaceholderSelection"
        :disabled="disabled" class="form-input cursor-pointer appearance-none pr-8" v-bind="$attrs"
        :aria-label="label ? undefined : (placeholder || 'Select Option')">

        <option v-if="placeholder" :disabled="!allowPlaceholderSelection" value="">
          {{ placeholder }}
        </option>

        <option v-for="option in options" :key="option.value" :value="option.value">
          {{ option.label }}
        </option>
      </select>

      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400">
        <Icon name="mdi:chevron-down" class="size-4" />
      </div>

    </div>
    <p v-if="error" class="text-red-600 text-xs mt-0.5">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
defineOptions({
  inheritAttrs: false,
});

interface SelectProps {
  name: string
  label?: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  options: {
    label: string
    value: string | number
  }[]
  error?: string | null
  modelValue: string | number | undefined
  allowPlaceholderSelection?: boolean
}

const props = withDefaults(defineProps<SelectProps>(), {
  allowPlaceholderSelection: false,
  modelValue: undefined,
})

const emit = defineEmits(['update:modelValue'])

const modelValueLocal = computed({
  get: () => props.modelValue ?? '',
  set: (val: string | number | undefined) => {
    const finalValue = val === '' ? undefined : val;
    emit('update:modelValue', finalValue);
  },
})
</script>
