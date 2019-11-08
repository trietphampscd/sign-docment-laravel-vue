import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Invoice from '@/views/apps/invoicing/Invoice'

Vue.use(BootstrapVue)

describe('Invoice.vue', () => {
  it('has a name', () => {
    expect(Invoice.name).toMatch('invoice')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Invoice)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Invoice', () => {
    const wrapper = shallowMount(Invoice)
    expect(wrapper.is(Invoice)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Invoice)
    expect(wrapper.find('div.card-header > div').text()).toMatch('Invoice')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(Invoice)
    expect(wrapper.element).toMatchSnapshot()
  })
})
