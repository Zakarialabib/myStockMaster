import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "MyStockMaster",
  description: "Comprehensive Code Wiki for MyStockMaster - Laravel v12 & Livewire v4 POS/ERP",
  themeConfig: {
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/architecture' }
    ],
    sidebar: [
      {
        text: 'Project Overview',
        items: [
          { text: 'Architecture', link: '/architecture' },
          { text: 'Major Modules', link: '/modules' },
          { text: 'Classes & Functions', link: '/classes-and-functions' },
          { text: 'Dependencies', link: '/dependencies' },
          { text: 'Running the Project', link: '/running-the-project' }
        ]
      }
    ],
    socialLinks: [
      { icon: 'github', link: 'https://github.com/zakarialabib/mystockmaster' }
    ]
  }
})
