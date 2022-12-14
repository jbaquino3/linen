import {_get, _post, _put, _delete} from './commons'

const resource = "transaction"

export const index = async (params) => {
    return await _get(`/api/${resource}`, params)
}

export const issued = async () => {
    return await _get(`/api/${resource}/issued`)
}

export const read = async (id) => {
    return await _get(`/api/${resource}/${id}`)
}

export const store = async (data) => {
    return await _post(`/api/${resource}`, data)
}

export const addItem = async (data, id) => {
    return await _post(`/api/${resource}/${id}`, data)
}

export const update = async (data, id) => {
    return await _put(`/api/${resource}/${id}`, data)
}

export const finalize = async (id) => {
    return await _put(`/api/${resource}/finalize/${id}`)
}

export const destroy = async (id) => {
    return await _delete(`/api/${resource}/${id}`)
}