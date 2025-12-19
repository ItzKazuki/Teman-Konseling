<template>
  <div class="space-y-2">
    <!-- Toolbar -->
    <div v-if="editor" class="flex flex-wrap gap-2 rounded-lg border border-gray-300 bg-gray-50 p-2">
      <button v-for="item in toolbarItems" :key="item.label || item.icon[1]" @click="item.action(editor)"
        :disabled="item.disabled && item.disabled(editor)" :class="[
          'px-3 py-1 text-sm font-medium rounded-md transition',
          editor.isActive(item.isActive?.name, item.isActive?.attrs)
            ? 'bg-primary-500 text-white'
            : 'bg-white text-gray-700 hover:bg-gray-100',
          item.disabled?.(editor) ? 'opacity-50 cursor-not-allowed' : ''
        ]">
        <Icon v-if="item.icon" :name="item.icon" />
        <span v-else>{{ item.label }}</span>
      </button>
    </div>

    <!-- Editor -->
    <TiptapEditorContent :editor="editor" />
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
      class:
        'rounded-md border border-slate-200 bg-white p-3 w-full h-80 overflow-auto prose prose-sm max-w-none transition duration-300 ease-in focus:outline-none focus:border-primary-500 hover:border-primary-300 shadow-sm focus:shadow'
    }
  }
});

const toolbarItems = [
  // Basic marks
  {
    icon: 'mdi:format-bold',
    action: (e) => e.chain().focus().toggleBold().run(),
    disabled: (e) => !e.can().chain().focus().toggleBold().run(),
    isActive: { name: 'bold' }
  },
  {
    icon: 'mdi:format-italic',
    action: (e) => e.chain().focus().toggleItalic().run(),
    disabled: (e) => !e.can().chain().focus().toggleItalic().run(),
    isActive: { name: 'italic' }
  },
  {
    icon: 'mdi:format-strikethrough',
    action: (e) => e.chain().focus().toggleStrike().run(),
    disabled: (e) => !e.can().chain().focus().toggleStrike().run(),
    isActive: { name: 'strike' }
  },
  {
    icon: 'mdi:code-tags',
    action: (e) => e.chain().focus().toggleCode().run(),
    isActive: { name: 'code' }
  },

  // Headings & Paragraph
  {
    icon: 'mdi:format-header-1',
    action: (e) => e.chain().focus().toggleHeading({ level: 1 }).run(),
    isActive: { name: 'heading', attrs: { level: 1 } }
  },
  {
    icon: 'mdi:format-header-2',
    action: (e) => e.chain().focus().toggleHeading({ level: 2 }).run(),
    isActive: { name: 'heading', attrs: { level: 2 } }
  },
  {
    icon: 'mdi:format-header-3',
    action: (e) => e.chain().focus().toggleHeading({ level: 3 }).run(),
    isActive: { name: 'heading', attrs: { level: 3 } }
  },
  {
    icon: 'mdi:format-paragraph',
    action: (e) => e.chain().focus().setParagraph().run(),
    isActive: { name: 'paragraph' }
  },

  // Lists
  {
    icon: 'mdi:format-list-bulleted',
    action: (e) => e.chain().focus().toggleBulletList().run(),
    isActive: { name: 'bulletList' }
  },
  {
    icon: 'mdi:format-list-numbered',
    action: (e) => e.chain().focus().toggleOrderedList().run(),
    isActive: { name: 'orderedList' }
  },

  // Blockquote & divider
  {
    icon: 'mdi:format-quote-close',
    action: (e) => e.chain().focus().toggleBlockquote().run(),
    isActive: { name: 'blockquote' }
  },
  {
    icon: 'mdi:minus',
    action: (e) => e.chain().focus().setHorizontalRule().run()
  },

  // Code block
  {
    icon: 'mdi:code-block-tags',
    action: (e) => e.chain().focus().toggleCodeBlock().run(),
    isActive: { name: 'codeBlock' }
  },

  // Others
  {
    icon: 'mdi:format-strikethrough-variant',
    action: (e) => e.chain().focus().unsetAllMarks().clearNodes().run()
  },
  {
    icon: 'mdi:arrow-down-right',
    action: (e) => e.chain().focus().setHardBreak().run()
  },

  // Undo/Redo
  {
    label: '⎌ Undo',
    action: (e) => e.chain().focus().undo().run(),
    disabled: (e) => !e.can().chain().focus().undo().run()
  },
  {
    label: '↻ Redo',
    action: (e) => e.chain().focus().redo().run(),
    disabled: (e) => !e.can().chain().focus().redo().run()
  }
];

onBeforeUnmount(() => {
  unref(editor).destroy();
});
</script>