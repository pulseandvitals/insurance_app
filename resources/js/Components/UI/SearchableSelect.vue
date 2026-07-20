<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    valid: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Search...',
    },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const query = ref(props.modelValue || '');
const root = ref(null);

watch(
    () => props.modelValue,
    (val) => {
        if (val !== query.value) query.value = val || '';
    },
);

const filtered = computed(() => {
    if (!query.value) return props.options.slice(0, 50);
    const q = query.value.toLowerCase();
    return props.options.filter((o) => o.toLowerCase().includes(q)).slice(0, 50);
});

function select(option) {
    query.value = option;
    emit('update:modelValue', option);
    open.value = false;
}

function onBlurCapture(e) {
    if (!root.value?.contains(e.relatedTarget)) {
        window.setTimeout(() => (open.value = false), 120);
    }
}
</script>

<template>
    <div ref="root" class="relative">
        <label v-if="label" :for="id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
            <span v-if="required" class="text-status-critical">*</span>
        </label>

        <input
            :id="id"
            v-model="query"
            type="text"
            :placeholder="placeholder"
            autocomplete="off"
            class="block w-full rounded-lg border py-2 text-sm shadow-sm transition focus:outline-none focus:ring-2"
            :class="
                error
                    ? 'border-status-critical focus:border-status-critical focus:ring-status-critical/30'
                    : valid
                      ? 'border-status-good focus:border-status-good focus:ring-status-good/30'
                      : 'border-gray-300 bg-white text-gray-900 focus:border-primary-500 focus:ring-primary-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100'
            "
            @focus="open = true"
            @blur="onBlurCapture"
            @input="open = true"
        />

        <ul
            v-if="open && filtered.length"
            class="absolute z-30 mt-1 max-h-56 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg dark:border-gray-700 dark:bg-gray-800"
        >
            <li
                v-for="option in filtered"
                :key="option"
                class="cursor-pointer px-3 py-1.5 text-gray-700 hover:bg-primary-50 hover:text-primary-700 dark:text-gray-200 dark:hover:bg-primary-950/40"
                @mousedown.prevent="select(option)"
            >
                {{ option }}
            </li>
        </ul>

        <p v-if="error" class="mt-1 text-xs font-medium text-status-critical">{{ error }}</p>
    </div>
</template>
