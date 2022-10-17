import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
    const user = ref({
        name: "John Israel"
    })
  
    return { user }
}, { persist: true })