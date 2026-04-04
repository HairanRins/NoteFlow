import { ref, readonly } from "vue";

const isDark = ref(true);

export function useTheme() {
  function toggle() {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle("dark", isDark.value);
  }

  return {
    isDark: readonly(isDark),
    toggle,
  };
}
