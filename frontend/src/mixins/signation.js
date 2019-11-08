import { post, put, remove, get } from '../utils/http'

export const signation = {
  methods: {
    /** SIGNATURE */
    getSignatures: function() {
      return get('signs-stamps/signs')
    },
    createSignature: function(data) {
      return post('signs-stamps/signs', data)
    },
    uploadSignature: function(data) {
      return post('signs-stamps/signs-upload', data)
    },
    getSignature: function(id) {
      return get('signs-stamps/signs/' + id)
    },
    softDeleteSignature: function(id) {
      return remove('signs-stamps/signs/'+id+'/delete')
    },
    destroySignature: function(id) {
      return remove('signs-stamps/signs/'+id+'/permanent')
    },
    
    /** Others */
    defaultSignature: function(id) {
      return get('signs-stamps/signs-default/'+id)
    }

    
  }
}
