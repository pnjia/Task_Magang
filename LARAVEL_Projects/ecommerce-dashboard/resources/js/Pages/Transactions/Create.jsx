// Transactions Create Page (POS/Kasir)
import { useState, useMemo } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { useForm, router } from '@inertiajs/react';

export default function TransactionsCreate({ products }) {
    const [search, setSearch] = useState('');
    const [cart, setCart] = useState([]);
    const [paymentAmount, setPaymentAmount] = useState(0);
    const [displayPayment, setDisplayPayment] = useState('');

    // Filter products based on search
    const filteredProducts = useMemo(() => {
        if (!search) return products;
        return products.filter(p => 
            p.name.toLowerCase().includes(search.toLowerCase())
        );
    }, [products, search]);

    // Calculate grand total
    const grandTotal = useMemo(() => {
        return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }, [cart]);

    // Calculate change
    const change = paymentAmount - grandTotal;

    // Format Rupiah
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number).replace('IDR', 'Rp');
    };

    // Add to cart
    const addToCart = (product) => {
        if (product.stock <= 0) {
            alert('Stok Habis!');
            return;
        }

        const existingIndex = cart.findIndex(item => item.id === product.id);

        if (existingIndex !== -1) {
            const newCart = [...cart];
            if (newCart[existingIndex].qty < product.stock) {
                newCart[existingIndex].qty++;
                setCart(newCart);
            } else {
                alert('Stok tidak cukup!');
            }
        } else {
            setCart([...cart, {
                id: product.id,
                name: product.name,
                price: product.price,
                qty: 1,
                maxStock: product.stock
            }]);
        }
    };

    // Update quantity
    const updateQty = (index, change) => {
        const newCart = [...cart];
        const item = newCart[index];
        const newQty = item.qty + change;

        if (newQty > item.maxStock) {
            alert('Stok mentok!');
            return;
        }

        if (newQty <= 0) {
            newCart.splice(index, 1);
        } else {
            item.qty = newQty;
        }

        setCart(newCart);
    };

    // Format payment input
    const formatPaymentInput = (value) => {
        const number = value.replace(/\D/g, '');
        const amount = number ? parseInt(number) : 0;
        setPaymentAmount(amount);
        
        if (number) {
            setDisplayPayment('Rp ' + parseInt(number).toLocaleString('id-ID'));
        } else {
            setDisplayPayment('');
        }
    };

    // Submit form
    const handleSubmit = (e) => {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Keranjang kosong!');
            return;
        }

        if (paymentAmount < grandTotal) {
            alert('Uang pembayaran kurang!');
            return;
        }

        router.post('/transactions', {
            payment_amount: paymentAmount,
            cart: cart.map(item => ({
                id: item.id,
                qty: item.qty
            }))
        });
    };

    return (
        <AppLayout title="Kasir (Point of Sales)">
            <div className="p-6">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Kasir (POS)</h2>
                    <p className="text-gray-600 mt-1">Sistem Point of Sales untuk transaksi penjualan</p>
                </div>

                <div className="flex flex-col md:flex-row gap-6">,
                        
                        {/* Products Section */}
                        <div className="w-full md:w-2/3">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-full">
                                <div className="flex justify-between items-center mb-4">
                                    <h3 className="text-lg font-bold text-gray-800">Pilih Produk</h3>
                                    <input 
                                        type="text" 
                                        value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        placeholder="Cari produk..."
                                        className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                    />
                                </div>

                                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto" style={{maxHeight: '600px'}}>
                                    {filteredProducts.map(product => (
                                        <div 
                                            key={product.id}
                                            onClick={() => addToCart(product)}
                                            className="border rounded-lg p-3 cursor-pointer hover:shadow-lg transition hover:border-indigo-500 flex flex-col items-center text-center bg-gray-50"
                                        >
                                            <div className="h-20 w-20 mb-2 rounded bg-gray-200 overflow-hidden">
                                                {product.image ? (
                                                    <img src={`/storage/${product.image}`} alt={product.name} className="h-full w-full object-cover" />
                                                ) : (
                                                    <div className="h-full w-full flex items-center justify-center text-gray-400 text-xs font-bold">
                                                        NO IMG
                                                    </div>
                                                )}
                                            </div>
                                            <h4 className="text-sm font-semibold text-gray-900 leading-tight">{product.name}</h4>
                                            <p className="text-xs text-gray-500 mt-1">Stok: {product.stock}</p>
                                            <p className="text-sm font-bold text-indigo-600 mt-1">{formatRupiah(product.price)}</p>
                                        </div>
                                    ))}

                                    {filteredProducts.length === 0 && (
                                        <div className="col-span-full text-center py-8 text-gray-500">
                                            Produk tidak ditemukan.
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Cart Section */}
                        <div className="w-full md:w-1/3">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full border-t-4 border-indigo-600">
                                
                                <div className="p-4 border-b bg-gray-50">
                                    <h3 className="text-lg font-bold text-gray-800">Keranjang Belanja</h3>
                                </div>

                                <div className="flex-1 p-4 overflow-y-auto bg-white relative" style={{maxHeight: '400px', minHeight: '300px'}}>
                                    {cart.length === 0 ? (
                                        <div className="absolute inset-0 flex flex-col items-center justify-center text-gray-400 space-y-2 opacity-50">
                                            <svg className="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p className="text-sm">Keranjang Kosong</p>
                                        </div>
                                    ) : (
                                        cart.map((item, index) => (
                                            <div key={item.id} className="flex justify-between items-start mb-4 border-b pb-2 last:border-0">
                                                <div className="flex-1 pr-2">
                                                    <h4 className="text-sm font-medium text-gray-900">{item.name}</h4>
                                                    <div className="text-xs text-gray-500">{formatRupiah(item.price)} x {item.qty}</div>
                                                </div>
                                                <div className="flex flex-col items-end space-y-1">
                                                    <span className="text-sm font-bold text-gray-800">{formatRupiah(item.price * item.qty)}</span>
                                                    
                                                    <div className="flex items-center border rounded bg-gray-50">
                                                        <button 
                                                            onClick={() => updateQty(index, -1)}
                                                            className="px-2 py-0.5 text-gray-600 hover:bg-gray-200 rounded-l"
                                                        >-</button>
                                                        <span className="px-2 text-xs font-bold">{item.qty}</span>
                                                        <button 
                                                            onClick={() => updateQty(index, 1)}
                                                            className="px-2 py-0.5 text-gray-600 hover:bg-gray-200 rounded-r"
                                                        >+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        ))
                                    )}
                                </div>

                                <div className="p-4 bg-gray-50 border-t">
                                    <div className="flex justify-between items-center mb-2">
                                        <span className="text-gray-600">Total Tagihan</span>
                                        <span className="text-xl font-bold text-gray-900">{formatRupiah(grandTotal)}</span>
                                    </div>

                                    <form onSubmit={handleSubmit}>
                                        <div className="mb-4">
                                            <label className="block text-xs font-medium text-gray-700 mb-1">Uang Diterima (Rp)</label>
                                            <input 
                                                type="text" 
                                                value={displayPayment}
                                                onChange={(e) => formatPaymentInput(e.target.value)}
                                                className="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-right font-mono font-bold text-lg"
                                                placeholder="Rp 0"
                                                required
                                            />
                                            <p className="text-xs text-gray-500 mt-1">Contoh: Rp 50.000 atau Rp 100.000</p>
                                        </div>

                                        <div className={`flex justify-between items-center mb-4 text-sm ${change < 0 ? 'text-red-500' : 'text-green-600'}`}>
                                            <span>Kembalian:</span>
                                            <span className="font-bold">{formatRupiah(change)}</span>
                                        </div>

                                        <button 
                                            type="submit"
                                            disabled={cart.length === 0 || paymentAmount < grandTotal}
                                            className={`w-full text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 text-base ${
                                                cart.length === 0 || paymentAmount < grandTotal
                                                    ? 'bg-gray-500 cursor-not-allowed'
                                                    : 'bg-indigo-600 hover:bg-indigo-700 hover:shadow-xl transform hover:-translate-y-0.5'
                                            }`}
                                        >
                                            {cart.length === 0 ? 'KERANJANG KOSONG' : 
                                             paymentAmount < grandTotal ? 'UANG KURANG' : 
                                             'BAYAR SEKARANG'}
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
        </AppLayout>
    );
}
