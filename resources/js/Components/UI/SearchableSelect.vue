<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';

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
    disabled: {
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
    /** When true, a query that matches nothing offers itself as a new entry instead of showing "no results". */
    creatable: {
        type: Boolean,
        default: false,
    },
    /** Noun used in the "register as new ___" hint, e.g. "brand", "color". */
    entryLabel: {
        type: String,
        default: 'entry',
    },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const query = ref(props.modelValue || '');
const root = ref(null);
const inputEl = ref(null);
const dropdownStyle = ref({});

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

const isNewEntry = computed(() => {
    if (!props.creatable || !query.value.trim()) return false;
    return ! props.options.some((o) => o.toLowerCase() === query.value.trim().toLowerCase());
});

// The dropdown is teleported to <body> and positioned with `fixed` coordinates
// so it isn't clipped by an ancestor with overflow:hidden/auto — e.g. a Modal's
// scrollable body, which would otherwise crop the list to invisibility.
function updatePosition() {
    if (!inputEl.value) return;
    const rect = inputEl.value.getBoundingClientRect();
    dropdownStyle.value = {
        position: 'fixed',
        top: `${rect.bottom + 4}px`,
        left: `${rect.left}px`,
        width: `${rect.width}px`,
    };
}

async function openDropdown() {
    open.value = true;
    await nextTick();
    updatePosition();
    window.addEventListener('scroll', updatePosition, true);
    window.addEventListener('resize', updatePosition);
}

function closeDropdown() {
    open.value = false;
    window.removeEventListener('scroll', updatePosition, true);
    window.removeEventListener('resize', updatePosition);
}

onBeforeUnmount(closeDropdown);

function select(option) {
    query.value = option;
    emit('update:modelValue', option);
    closeDropdown();
}

function onInput() {
    if (!open.value) openDropdown();
    emit('update:modelValue', query.value);
}

function onBlurCapture(e) {
    if (!root.value?.contains(e.relatedTarget)) {
        window.setTimeout(closeDropdown, 120);
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
            ref="inputEl"
            v-model="query"
            type="text"
            :placeholder="placeholder"
            :disabled="disabled"
            autocomplete="off"
            class="block w-full rounded-lg border py-2 text-sm shadow-sm transition focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400 dark:disabled:bg-gray-800"
            :class="
                error
                    ? 'border-status-critical focus:border-status-critical focus:ring-status-critical/30'
                    : valid
                      ? 'border-status-good focus:border-status-good focus:ring-status-good/30'
                      : 'border-gray-300 bg-white text-gray-900 focus:border-primary-500 focus:ring-primary-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100'
            "
            @focus="openDropdown"
            @blur="onBlurCapture"
            @input="onInput"
        />

        <Teleport to="body">
            <ul
                v-if="open && (filtered.length || isNewEntry)"
                :style="dropdownStyle"
                class="z-[100] max-h-56 overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg dark:border-gray-700 dark:bg-gray-800"
            >
                <li
                    v-for="option in filtered"
                    :key="option"
                    class="cursor-pointer px-3 py-1.5 text-gray-700 hover:bg-primary-50 hover:text-primary-700 dark:text-gray-200 dark:hover:bg-primary-950/40"
                    @mousedown.prevent="select(option)"
                >
                    {{ option }}
                </li>
                <li
                    v-if="isNewEntry"
                    class="flex items-center gap-1.5 border-t border-gray-100 px-3 py-1.5 text-primary-600 dark:border-gray-700 dark:text-primary-400"
                >
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Register "{{ query }}" as a new {{ entryLabel }}
                </li>
            </ul>
        </Teleport>

        <p v-if="error" class="mt-1 text-xs font-medium text-status-critical">{{ error }}</p>
    </div>
</template>
