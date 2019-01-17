using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using CapiValidation.Data.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace CapiValidation.Data
{
    public class PartialRepository<T> : IPartialRepository<T> where T : class, IEntityBase
    {
        private readonly DbContext _context;

        public PartialRepository(DbContext context)
            => _context = context;

        public virtual async Task<IEnumerable<T>> ListAsync()
            => await _context.Set<T>().ToListAsync();

        public virtual async Task<IEnumerable<T>> ListAsync(ISpecification<T> spec)
        {
            var queryableResultWithIncludes = spec.Includes.Aggregate(_context.Set<T>().AsQueryable(), (current, include) => current.Include(include));

            var secondaryResult = spec.IncludeStrings.Aggregate(queryableResultWithIncludes, (current, include) => current.Include(include));

            return await secondaryResult.Where(spec.Criteria).ToListAsync();
        }

        public virtual async Task<T> GetByIdAsync(params object[] id)
            => await _context.Set<T>().FindAsync(id);
    }
}