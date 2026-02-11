import React, { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import { Link, router, useForm } from '@inertiajs/react';

export default function TransactionsIndex({ transactions, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [dateFrom, setDateFrom] = useState(filters.date_from || '');
    const [dateTo, setDateTo] = useState(filters.date_to || '');
    const [filterStatus, setFilterStatus] = useState(filters.filter_status || '');
    const [filterOpen, setFilterOpen] = useState(false);

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number).replace('IDR', 'Rp');
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const getStatusBadge = (status) => {
        const statusColors = {
            'unpaid': 'bg-red-100 text-red-800',
            'paid': 'bg-yellow-100 text-yellow-800',
            'processing': 'bg-blue-100 text-blue-800',
            'shipped': 'bg-purple-100 text-purple-800',
        };

        const statusLabels = {
            'unpaid': 'Belum Bayar',
            'paid': 'Kirim (Ship)',
            'processing': 'Proses',
            'shipped': 'Dikirim'
        };

        return (
            <span className={`px-2 py-1 text-xs font-semibold rounded ${statusColors[status]}`}>
                {statusLabels[status]}
            </span>
        );
    };

    const handleFilter = () => {
        router.get('/transactions', {
            search,
            date_from: dateFrom,
            date_to: dateTo,
            filter_status: filterStatus
        }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const handleReset = () => {
        setSearch('');
        setDateFrom('');
        setDateTo('');
        setFilterStatus('');
        router.get('/transactions');
    };

    const getStatusColor = (status) => {
        const colors = {
            'unpaid': 'bg-red-100 text-red-800 border-red-300',
            'paid': 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'processing': 'bg-blue-100 text-blue-800 border-blue-300',
            'shipped': 'bg-purple-100 text-purple-800 border-purple-300',
            'completed': 'bg-green-100 text-green-800 border-green-300',
            'cancelled': 'bg-gray-100 text-gray-800 border-gray-300'
        };
        return colors[status] || 'bg-gray-100 text-gray-800 border-gray-300';
    };

    const handleStatusChange = (transactionId, newStatus) => {
        router.put(`/transactions/${transactionId}/status`, 
            { status: newStatus },
            {
                preserveScroll: true,
                onBefore: () => true,
                onSuccess: () => {
                    router.reload({ only: ['transactions'] });
                }
            }
        );
    };

    return (
        <AppLayout title="Pesanan Masuk">
            <div className="p-6">
                <div className="mb-6">
                    <h2 className="text-2xl font-bold text-gray-800">Pesanan Masuk</h2>
                    <p className="text-gray-600 mt-1">Kelola pesanan yang masuk dan perlu diproses</p>
                </div>

                <div className="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div className="p-6">
                        {/* Search and Filter */}
                        <div className="mb-6 space-y-4">,
                                <div className="flex gap-4">
                                    <div className="flex-1">
                                        <input
                                            type="text"
                                            value={search}
                                            onChange={(e) => setSearch(e.target.value)}
                                            placeholder="Cari invoice..."
                                            className="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        />
                                    </div>
                                    <button
                                        onClick={() => setFilterOpen(!filterOpen)}
                                        className="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 flex items-center gap-2"
                                    >
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                        </svg>
                                        Filter
                                    </button>
                                </div>

                                {filterOpen && (
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                            <input
                                                type="date"
                                                value={dateFrom}
                                                onChange={(e) => setDateFrom(e.target.value)}
                                                className="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                            <input
                                                type="date"
                                                value={dateTo}
                                                onChange={(e) => setDateTo(e.target.value)}
                                                className="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                            <select
                                                value={filterStatus}
                                                onChange={(e) => setFilterStatus(e.target.value)}
                                                className="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            >
                                                <option value="">Semua Status</option>
                                                <option value="unpaid">Belum Bayar</option>
                                                <option value="paid">Kirim (Ship)</option>
                                                <option value="processing">Proses</option>
                                                <option value="shipped">Dikirim</option>
                                            </select>
                                        </div>
                                        <div className="col-span-3 flex gap-2">
                                            <button
                                                onClick={handleFilter}
                                                className="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                            >
                                                Terapkan Filter
                                            </button>
                                            <button
                                                onClick={handleReset}
                                                className="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                                            >
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Table */}
                            <div className="overflow-x-auto border border-gray-200 rounded-lg">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Status</th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {transactions.data.map(transaction => (
                                            <tr key={transaction.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {formatDate(transaction.transaction_date)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {transaction.invoice_code}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Pelanggan Umum
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                                    {formatRupiah(transaction.total_amount)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <select
                                                        value={transaction.status}
                                                        onChange={(e) => handleStatusChange(transaction.id, e.target.value)}
                                                        className={`text-sm font-medium rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer ${getStatusColor(transaction.status)}`}
                                                    >
                                                        <option value="unpaid" className="bg-white text-gray-800">Belum Bayar</option>
                                                        <option value="paid" className="bg-white text-gray-800">Sudah Bayar</option>
                                                        <option value="processing" className="bg-white text-gray-800">Diproses</option>
                                                        <option value="shipped" className="bg-white text-gray-800">Dikirim</option>
                                                        <option value="completed" className="bg-white text-gray-800">Selesai</option>
                                                        <option value="cancelled" className="bg-white text-gray-800">Dibatalkan</option>
                                                    </select>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                    <Link
                                                        href={`/transactions/${transaction.id}`}
                                                        className="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {transactions.links && transactions.links.length > 3 && (
                                <div className="mt-6 flex justify-between items-center">
                                    <div className="text-sm text-gray-700">
                                        Menampilkan {transactions.from} sampai {transactions.to} dari {transactions.total} pesanan
                                    </div>
                                    <div className="flex gap-2">
                                        {transactions.links.map((link, index) => (
                                            <Link
                                                key={index}
                                                href={link.url || '#'}
                                                className={`px-3 py-1 border rounded ${link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50'} ${!link.url && 'opacity-50 cursor-not-allowed'}`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
