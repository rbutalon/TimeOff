export default {
    server: {
      proxy: {
        '/api': {
          target: 'http://localhost/timeoff-backend',
          changeOrigin: true,
          rewrite: (path) => path.replace(/^\/api/, '')
        }
      }
    }
  }