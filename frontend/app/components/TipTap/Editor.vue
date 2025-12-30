<template>
  <div
    class="space-y-3 border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary-500/20 transition-all">
    <div v-if="editor"
      class="flex flex-wrap items-center gap-1 bg-gray-50/80 backdrop-blur-sm p-1.5 border-b border-gray-200">

      <div v-for="(group, gIdx) in toolbarGroups" :key="gIdx"
        class="flex items-center gap-1 pr-1 mr-1 border-r border-gray-300 last:border-0 last:mr-0 last:pr-0">
        <button v-for="item in group" :key="item.icon" @click="item.action(editor)" :title="item.tooltip"
          :disabled="item.disabled && item.disabled(editor)" type="button" :class="[
            'p-2 rounded-lg transition-all duration-200 flex items-center justify-center',
            editor.isActive(item.isActive?.name, item.isActive?.attrs)
              ? 'bg-primary-600 text-white shadow-sm'
              : 'text-gray-600 hover:bg-gray-200 hover:text-gray-900',
            item.disabled?.(editor) ? 'opacity-30 cursor-not-allowed' : ''
          ]">
          <Icon :name="item.icon" class="w-5 h-5" />
        </button>
      </div>

    </div>

    <TiptapEditorContent :editor="editor" class="bg-white" />
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
  content: props.modelValue,
  extensions: [TiptapStarterKit],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML());
  },
  editorProps: {
    attributes: {
      class: 'prose prose-sm max-w-none p-5 min-h-[300px] focus:outline-none'
    }
  }
});

const toolbarGroups = [
  // History
  [
    { icon: 'tabler:arrow-back-up', tooltip: 'Undo', action: (e) => e.chain().focus().undo().run(), disabled: (e) => !e.can().undo() },
    { icon: 'tabler:arrow-forward-up', tooltip: 'Redo', action: (e) => e.chain().focus().redo().run(), disabled: (e) => !e.can().redo() },
  ],
  // Basic Formatting
  [
    { icon: 'tabler:bold', tooltip: 'Bold', action: (e) => e.chain().focus().toggleBold().run(), isActive: { name: 'bold' } },
    { icon: 'tabler:italic', tooltip: 'Italic', action: (e) => e.chain().focus().toggleItalic().run(), isActive: { name: 'italic' } },
    { icon: 'tabler:strikethrough', tooltip: 'Strike', action: (e) => e.chain().focus().toggleStrike().run(), isActive: { name: 'strike' } },
    { icon: 'tabler:code', tooltip: 'Inline Code', action: (e) => e.chain().focus().toggleCode().run(), isActive: { name: 'code' } },
  ],
  // Typography
  [
    { icon: 'tabler:h-1', tooltip: 'Heading 1', action: (e) => e.chain().focus().toggleHeading({ level: 1 }).run(), isActive: { name: 'heading', attrs: { level: 1 } } },
    { icon: 'tabler:h-2', tooltip: 'Heading 2', action: (e) => e.chain().focus().toggleHeading({ level: 2 }).run(), isActive: { name: 'heading', attrs: { level: 2 } } },
    { icon: 'tabler:list', tooltip: 'Bullet List', action: (e) => e.chain().focus().toggleBulletList().run(), isActive: { name: 'bulletList' } },
    { icon: 'tabler:list-numbers', tooltip: 'Ordered List', action: (e) => e.chain().focus().toggleOrderedList().run(), isActive: { name: 'orderedList' } },
  ],
  // Special
  [
    { icon: 'tabler:quote', tooltip: 'Blockquote', action: (e) => e.chain().focus().toggleBlockquote().run(), isActive: { name: 'blockquote' } },
    { icon: 'tabler:terminal-2', tooltip: 'Code Block', action: (e) => e.chain().focus().toggleCodeBlock().run(), isActive: { name: 'codeBlock' } },
    { icon: 'tabler:separator', tooltip: 'Divider', action: (e) => e.chain().focus().setHorizontalRule().run() },
  ],
  // Clean up
  [
    { icon: 'tabler:eraser', tooltip: 'Clear Format', action: (e) => e.chain().focus().unsetAllMarks().clearNodes().run() },
  ]
];

onBeforeUnmount(() => {
  unref(editor).destroy();
});
</script>