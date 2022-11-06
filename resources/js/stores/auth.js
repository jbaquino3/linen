import { defineStore } from 'pinia'
import { ref } from 'vue'
import { index } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const user_loading = ref(true)

    async function fetchUser() {
        user_loading.value = true
        const res = await index()
        if(res.status) {
            user.value = res.data
        }
        user_loading.value = false
    }
  
    return { user, fetchUser }
})