import { post, put, remove, get } from '../utils/http'

export const stampseal = {
  methods: {
    /** STAMPS */
    getStamps: function() {
      return get('signs-stamps/stamps')
    },
    createStamp: function(data) {
      return post('signs-stamps/stamps', data)
    },
    uploadStamp: function(data) {
      return post('signs-stamps/stamps-upload', data)
    },
    getStamp: function(id) {
      return get('signs-stamps/stamps/' + id)
    },
    softDeleteStamp: function(id) {
      return remove('signs-stamps/stamps/'+id+'/delete')
    },
    destroyStamp: function(id) {
      return remove('signs-stamps/stamps/'+id+'/permanent')
    },

    /** Others */
    defaultStamp: function(id) {
      return post('signs-stamps/stamps-default/'+id)
    }
  }
}