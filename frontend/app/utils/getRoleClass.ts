export default function (role?: string) {
  switch (role) {
    case 'bk':
      return 'bg-purple-100 text-purple-800 border-purple-200';
    case 'guru':
      return 'bg-indigo-100 text-indigo-800 border-indigo-200';
    case 'admin':
      return 'bg-red-100 text-red-800 border-red-200';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-200';
  }
}