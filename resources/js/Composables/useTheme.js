import { ref, watchEffect } from 'vue';

const STORAGE_KEY = 'insurapp-theme';

const theme = ref(
    localStorage.getItem(STORAGE_KEY) ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
);

function applyTheme(value) {
    document.documentElement.classList.toggle('dark', value === 'dark');
}

watchEffect(() => {
    applyTheme(theme.value);
    localStorage.setItem(STORAGE_KEY, theme.value);
});

export function useTheme() {
    const toggleTheme = () => {
        theme.value = theme.value === 'dark' ? 'light' : 'dark';
    };

    const setTheme = (value) => {
        theme.value = value;
    };

    return { theme, toggleTheme, setTheme };
}
