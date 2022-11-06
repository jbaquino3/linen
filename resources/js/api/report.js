import {_get, _post, _put, _delete} from './commons'

const resource = "report"

export const index = async () => {
    return await _get(`/api/${resource}`)
}

export const read = async (id) => {
    return await _get(`/api/${resource}/${id}`)
}

export const destroy = async (id) => {
    return await _delete(`/api/${resource}/${id}`)
}

export const generate = async (data) => {
    return await _post(`/api/${resource}/generate`, data)
}