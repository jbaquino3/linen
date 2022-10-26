import { getCurrentInstance } from 'vue'

export function useRouter() {
    const instance = getCurrentInstance()
    if (!instance) {
        throw new Error(`useRouter should be called in setup().`)
    }
    return instance.proxy.$router
}