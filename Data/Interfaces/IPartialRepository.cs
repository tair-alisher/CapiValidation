using System.Collections.Generic;
using System.Threading.Tasks;

namespace CapiValidation.Data.Interfaces
{
    public interface IPartialRepository { }

    public interface IPartialRepository<T> : IPartialRepository where T : class, IEntityBase
    {
        Task<IEnumerable<T>> ListAsync();
        Task<IEnumerable<T>> ListAsync(ISpecification<T> spec);
        Task<int> CountAsync();
        Task<T> GetByIdAsync(params object[] id);
    }
}