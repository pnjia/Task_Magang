// Profile Edit Page
import { useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';

export default function Edit() {
    const { user } = usePage().props;
    const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);

    // Profile Information Form
    const { data: profileData, setData: setProfileData, patch: updateProfile, processing: updatingProfile, errors: profileErrors } = useForm({
        name: user.name || '',
        email: user.email || '',
    });

    // Update Password Form
    const { data: passwordData, setData: setPasswordData, put: updatePassword, processing: updatingPassword, errors: passwordErrors, reset: resetPassword } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    // Delete Account Form
    const { data: deleteData, setData: setDeleteData, delete: deleteAccount, processing: deletingAccount, errors: deleteErrors } = useForm({
        password: '',
    });

    const handleProfileUpdate = (e) => {
        e.preventDefault();
        updateProfile('/profile', {
            preserveScroll: true,
        });
    };

    const handlePasswordUpdate = (e) => {
        e.preventDefault();
        updatePassword('/profile', {
            preserveScroll: true,
            onSuccess: () => resetPassword(),
        });
    };

    const handleDeleteAccount = (e) => {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
            deleteAccount('/profile', {
                preserveScroll: true,
            });
        }
        setShowDeleteConfirm(false);
    };

    return (
        <AppLayout title="Profile">
            <div className="p-6 max-w-4xl">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold text-gray-800">Profile</h1>
                    <p className="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
                </div>

                <div className="space-y-6">
                    {/* Profile Information */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div className="mb-6">
                            <h2 className="text-lg font-semibold text-gray-800">Profile Information</h2>
                            <p className="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
                        </div>

                        <form onSubmit={handleProfileUpdate} className="space-y-4">
                            <div>
                                <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                                    Name
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    value={profileData.name}
                                    onChange={e => setProfileData('name', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ${
                                        profileErrors.name ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                {profileErrors.name && (
                                    <p className="mt-1 text-sm text-red-600">{profileErrors.name}</p>
                                )}
                            </div>

                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    value={profileData.email}
                                    onChange={e => setProfileData('email', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ${
                                        profileErrors.email ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                {profileErrors.email && (
                                    <p className="mt-1 text-sm text-red-600">{profileErrors.email}</p>
                                )}
                            </div>

                            <div className="pt-4">
                                <button
                                    type="submit"
                                    disabled={updatingProfile}
                                    className={`px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors ${
                                        updatingProfile ? 'opacity-50 cursor-not-allowed' : ''
                                    }`}>
                                    {updatingProfile ? 'Saving...' : 'SAVE'}
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* Update Password */}
                    <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div className="mb-6">
                            <h2 className="text-lg font-semibold text-gray-800">Update Password</h2>
                            <p className="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
                        </div>

                        <form onSubmit={handlePasswordUpdate} className="space-y-4">
                            <div>
                                <label htmlFor="current_password" className="block text-sm font-medium text-gray-700 mb-2">
                                    Current Password
                                </label>
                                <input
                                    type="password"
                                    id="current_password"
                                    value={passwordData.current_password}
                                    onChange={e => setPasswordData('current_password', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ${
                                        passwordErrors.current_password ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                />
                                {passwordErrors.current_password && (
                                    <p className="mt-1 text-sm text-red-600">{passwordErrors.current_password}</p>
                                )}
                            </div>

                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    value={passwordData.password}
                                    onChange={e => setPasswordData('password', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ${
                                        passwordErrors.password ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                />
                                {passwordErrors.password && (
                                    <p className="mt-1 text-sm text-red-600">{passwordErrors.password}</p>
                                )}
                            </div>

                            <div>
                                <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    value={passwordData.password_confirmation}
                                    onChange={e => setPasswordData('password_confirmation', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent ${
                                        passwordErrors.password_confirmation ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                />
                                {passwordErrors.password_confirmation && (
                                    <p className="mt-1 text-sm text-red-600">{passwordErrors.password_confirmation}</p>
                                )}
                            </div>

                            <div className="pt-4">
                                <button
                                    type="submit"
                                    disabled={updatingPassword}
                                    className={`px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors ${
                                        updatingPassword ? 'opacity-50 cursor-not-allowed' : ''
                                    }`}>
                                    {updatingPassword ? 'Saving...' : 'SAVE'}
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* Delete Account */}
                    <div className="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                        <div className="mb-6">
                            <h2 className="text-lg font-semibold text-red-800">Delete Account</h2>
                            <p className="text-sm text-gray-600 mt-1">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                            </p>
                        </div>

                        {!showDeleteConfirm ? (
                            <button
                                onClick={() => setShowDeleteConfirm(true)}
                                className="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Delete Account
                            </button>
                        ) : (
                            <form onSubmit={handleDeleteAccount} className="space-y-4">
                                <div>
                                    <label htmlFor="delete_password" className="block text-sm font-medium text-gray-700 mb-2">
                                        Password
                                    </label>
                                    <input
                                        type="password"
                                        id="delete_password"
                                        value={deleteData.password}
                                        onChange={e => setDeleteData('password', e.target.value)}
                                        placeholder="Enter your password to confirm"
                                        className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent ${
                                            deleteErrors.password ? 'border-red-500' : 'border-gray-300'
                                        }`}
                                        required
                                    />
                                    {deleteErrors.password && (
                                        <p className="mt-1 text-sm text-red-600">{deleteErrors.password}</p>
                                    )}
                                </div>

                                <div className="flex gap-3">
                                    <button
                                        type="submit"
                                        disabled={deletingAccount}
                                        className={`px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors ${
                                            deletingAccount ? 'opacity-50 cursor-not-allowed' : ''
                                        }`}>
                                        {deletingAccount ? 'Deleting...' : 'Delete Account'}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowDeleteConfirm(false)}
                                        className="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
