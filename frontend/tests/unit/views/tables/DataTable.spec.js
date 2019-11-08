import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import DataTable from '@/views/tables/DataTable'


Vue.use(BootstrapVue)

describe('DataTable.vue', () => {
  it('has a name', () => {
    expect(DataTable.name).toMatch('DataTable')
  })
  it('has a created hook', () => {
    expect(typeof DataTable.data).toMatch('function')
  })
  it('sets the correct default data', () => {
    expect(typeof DataTable.data).toMatch('function')
    const defaultData = DataTable.data()
    expect(defaultData.useVuex).toBe(false)
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(DataTable)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is DataTable', () => {
    const wrapper = shallowMount(DataTable)
    expect(wrapper.is(DataTable)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(DataTable)
    expect(wrapper.find('div.card-header').text()).toMatch('Data Table')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(DataTable)
    expect(wrapper.element).toMatchSnapshot()
  })
})
