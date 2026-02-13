import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { formatRelativeTime } from '@/Utils/formatDate';

export default function Dashboard({ 
    totalRevenue, 
    totalTransactions, 
    totalProducts, 
    lowStockProducts, 
    recentTransactions 
}) {
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount).replace('IDR', 'Rp');
    };

    return (
        <AppLayout title="Dashboard">
            <div className="p-6">
                <div className="mb-6 flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-800">Dashboard</h2>
                        <p className="text-gray-600 mt-1">Ringkasan bisnis dan performa toko Anda</p>
                    </div>
                    <a
                        href="/dashboard/export-sales"
                        className="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out"
                    >
                        <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor Data
                    </a>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                            <div className="text-gray-500 text-sm font-medium uppercase">Total Pendapatan</div>
                            <div className="text-2xl font-bold text-gray-800 mt-2">
                                {formatCurrency(totalRevenue)}
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                            <div className="text-gray-500 text-sm font-medium uppercase">Total Transaksi</div>
                            <div className="text-2xl font-bold text-gray-800 mt-2">
                                {totalTransactions} <span className="text-sm font-normal text-gray-400">Nota</span>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                            <div className="text-gray-500 text-sm font-medium uppercase">Total Produk</div>
                            <div className="text-2xl font-bold text-gray-800 mt-2">
                                {totalProducts} <span className="text-sm font-normal text-gray-400">SKU</span>
                            </div>
                        </div>
                    </div>

                    {/* Low Stock & Recent Transactions */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {/* Low Stock Products */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                            <div className="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 className="font-bold text-gray-700">⚠️ Stok Menipis (&lt; 10)</h3>
                                <Link 
                                    href="/products"
                                    className="text-xs text-indigo-600 hover:underline font-semibold">
                                    KELOLA
                                </Link>
                            </div>
                            <div className="p-4">
                                {lowStockProducts && lowStockProducts.length > 0 ? (
                                    <ul className="space-y-3">
                                        {lowStockProducts.map((product) => (
                                            <li 
                                                key={product.id}
                                                className="flex justify-between items-center bg-red-50 p-3 rounded border border-red-100">
                                                <div>
                                                    <div className="font-bold text-gray-800 text-sm">{product.name}</div>
                                                    <div className="text-xs text-gray-500">
                                                        {product.category?.name || '-'}
                                                    </div>
                                                </div>
                                                <div className="text-center">
                                                    <span className="block text-xs text-gray-500">Sisa</span>
                                                    <span className="text-red-600 font-bold text-lg">{product.stock}</span>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                ) : (
                                    <div className="flex flex-col items-center justify-center py-8 text-gray-400">
                                        <svg className="w-12 h-12 mb-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p className="text-sm">Stok aman terkendali.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Recent Transactions */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                            <div className="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 className="font-bold text-gray-700">Transaksi Terakhir</h3>
                                <Link 
                                    href="/transactions/history"
                                    className="text-xs text-indigo-600 hover:underline font-semibold">
                                    SEMUA
                                </Link>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm text-left text-gray-600">
                                    <thead className="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                                        <tr>
                                            <th className="px-4 py-3">Invoice</th>
                                            <th className="px-4 py-3">Total</th>
                                            <th className="px-4 py-3 text-right">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {recentTransactions && recentTransactions.length > 0 ? (
                                            recentTransactions.map((trx) => (
                                                <tr key={trx.id} className="bg-white border-b hover:bg-gray-50">
                                                    <td className="px-4 py-3 font-medium text-gray-900">{trx.invoice_code}</td>
                                                    <td className="px-4 py-3 font-bold text-green-600">
                                                        {formatCurrency(trx.total_amount)}
                                                    </td>
                                                    <td className="px-4 py-3 text-right text-xs text-gray-500">
                                                        {formatRelativeTime(trx.created_at)}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="3" className="px-4 py-6 text-center italic text-gray-400">
                                                    Belum ada penjualan hari ini.
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
