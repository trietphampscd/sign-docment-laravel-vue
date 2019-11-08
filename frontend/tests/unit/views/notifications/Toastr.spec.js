import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Toastr from '@/views/notifications/Toastr'

Vue.use(BootstrapVue)

describe('Toastr.vue', () => {
  it('has a name', () => {
    expect(Toastr.name).toMatch('toastr')
  })
  it('has a created hook', () => {
    expect(typeof Toastr.data).toMatch('function')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Toastr)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Toastr', () => {
    const wrapper = shallowMount(Toastr)
    expect(wrapper.is(Toastr)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Toastr)
    expect(wrapper.find('div.card-header > div').text()).toMatch('Toastr')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(Toastr)
    expect(wrapper.element).toMatchSnapshot()
  })
})
