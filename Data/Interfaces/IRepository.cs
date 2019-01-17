using System.Collections.Generic;
using System.Threading.Tasks;

namespace CapiValidation.Data.Interfaces
{
    public interface IRepository { }

    public interface IRepository<T> : IRepository where T : class, IEntityBase
    {
        Task<IEnumerable<T>> ListAsync();
        Task<IEnumerable<T>> ListAsync(ISpecification<T> spec);
        Task<T> GetByIdAsync(params object[] id);
        Task InsertAsync(T entity);
        Task InsertAsync(IEnumerable<T> entities);
        void Update(T entity);
        void Update(IEnumerable<T> entities);
        void Delete(T entity);
        void Delete(params object[] id);
        void Delete(IEnumerable<T> entities);
    }
}