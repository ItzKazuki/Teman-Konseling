export const useDataTable = <T, F extends object>(url: string, initialFilters: F) => {
  const data = ref<T[]>([]);
  const loading = ref(false);
  
  const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
    has_more_pages: false
  });

  const filters = reactive({ ...initialFilters, page: 1 }) as F & { page: number };

  const fetchData = async () => {
    loading.value = true;
    try {
      // Mengirim query params seperti ?page=1&search=...
      const res = await useApi().get<any>(url, { params: filters });
      
      if (res.status && res.pagination) {
        // MAPPING DISINI:
        data.value = res.data;       // List siswa ada di res.data
        meta.value = res.pagination; // Info halaman ada di res.pagination
      }
    } catch (e) {
      console.error('Fetch error:', e);
    } finally {
      loading.value = false;
    }
  };

  const changePage = (newPage: number) => {
    if (newPage >= 1 && newPage <= meta.value.last_page) {
      filters.page = newPage;
      fetchData();
    }
  };

  const applyFilter = () => {
    filters.page = 1;
    fetchData();
  };

  return { data, loading, meta, filters, fetchData, changePage, applyFilter };
};