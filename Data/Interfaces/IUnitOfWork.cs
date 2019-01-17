using System;
using System.Threading.Tasks;

namespace CapiValidation.Data.Interfaces
{
    public interface IUnitOfWork : IDisposable
    {
        IRepository<T> GetRepository<T>() where T : class, IEntityBase;
        IPartialRepository<T> GetPartialRepository<T>() where T : class, IEntityBase;
        void SaveChanges();
        Task SaveChangesAsync();
    }
}