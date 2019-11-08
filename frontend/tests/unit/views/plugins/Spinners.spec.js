import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Spinners from '@/views/plugins/Spinners'

Vue.use(BootstrapVue)

describe('Spinners.vue', () => {
  it('has a name', () => {
    expect(Spinners.name).toMatch('spinners')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Spinners)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Spinners', () => {
    const wrapper = shallowMount(Spinners)
    expect(wrapper.is(Spinners)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Spinners)
    expect(wrapper.find('div.card-header').text()).toMatch('Spinners - SpinKit')
  })
})
