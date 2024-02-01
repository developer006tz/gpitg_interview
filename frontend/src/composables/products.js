import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'GET, POST, PATCH, PUT, DELETE, OPTIONS';
axios.defaults.baseURL = 'http://localhost:8000/api/v1';
// user bearer token
// `Bearer ${localStorage.getItem('token')}`; //if i implement auth in the future
let token = "16|5AkDDanowHp0I44HPcRATebaypkhxAJqYVeYOvdt82c1ad51"; 
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

export default function UseProducts() {
    const products = ref([])
    const product = ref({})
    const loading = ref(false)
    const error = ref(null)
    const success = ref(null)
    const router = useRouter()


    const fetchProducts = async () => {
        loading.value = true
        try {
            const response = await axios.get("/products")
            products.value = response.data.products

        } catch (err) {
            if (err.response.status === 422) {
                error.value = err.response.data.errors
            }
        } finally {
            loading.value = false
        }
    }
    const fetchProduct = async (id) => {
        loading.value = true
        try {
            const response = await axios.get("/products/" + id)
            product.value = response.data.product
        } catch (err) {
            if (err.response.status === 422) {
                error.value = err.response.data.errors
            }
        } finally {
            loading.value = false
        }
    }
    const createProduct = async (product) => {
        loading.value = true
        try {
            const response = await axios.post('/products', product)
            success.value = response.data
            router.push({ name: 'Products' })
        } catch (err) {
            if (err.response.status === 422) {
                if (err.response.status === 422) {
                    error.value = err.response.data.errors
                }
            }
        } finally {
            loading.value = false
        }
    }
    const updateProduct = async (product) => {
        loading.value = true
        try {
            const response = await axios.put("/products/" + id, product)
            success.value = response.data
        } catch (err) {
            if (err.response.status === 422) {
                error.value = err.response.data.errors
            }
        } finally {
            loading.value = false
        }
    }
    const deleteProduct = async (id) => {
        loading.value = true
        try {
            const response = await axios.delete("/products/" + id)
            success.value = response.data.message
        } catch (err) {
            if (err.response.status === 422) {
                error.value = err.response.data.errors
            }
        } finally {
            loading.value = false
        }
    }
    return {
        products,
        product,
        loading,
        error,
        success,
        fetchProducts,
        fetchProduct,
        createProduct,
        updateProduct,
        deleteProduct,
    }
}