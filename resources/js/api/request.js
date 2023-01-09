import {_get, _post, _put, _delete} from './commons'

const resource = "request"

export const index = async () => {
    return await _get(`/api/${resource}`)
}

export const stats = async () => {
    return await _get(`/api/${resource}/stats`)
}

export const read = async (id) => {
    return await _get(`/api/${resource}/${id}`)
}

export const store = async (data) => {
    return await _post(`/api/${resource}`, data)
}

export const createRemark = async (data) => {
    return await _post(`/api/${resource}/remarks`, data)
}

export const processRequest = async (id) => {
    return await _put(`/api/${resource}/process/${id}`)
}

export const readyRequest = async (id) => {
    return await _put(`/api/${resource}/ready/${id}`)
}

export const issueRequest = async (id) => {
    return await _put(`/api/${resource}/issue/${id}`)
}

export const update = async (data, id) => {
    return await _put(`/api/${resource}/${id}`, data)
}

export const destroy = async (id) => {
    return await _delete(`/api/${resource}/${id}`)
}