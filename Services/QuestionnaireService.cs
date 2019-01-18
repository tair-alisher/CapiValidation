using System.Collections.Generic;
using System.Threading.Tasks;
using System.Linq;
using CapiValidation.Data.Entities;
using CapiValidation.Data.Interfaces;
using CapiValidation.Services.Interfaces;
using CapiValidation.Data;

namespace CapiValidation.Services
{
    public class QuestionnaireService : IQuestionnaireService
    {
        private readonly IUnitOfWork _uow;

        public QuestionnaireService(IUnitOfWork uow)
            => _uow = uow;

        public async Task<IEnumerable<Questionnaire>> GetAllAsync()
            => (await _uow.GetPartialRepository<Questionnaire>().ListAsync()).OrderBy(q => q.Title);

        public async Task<Questionnaire> GetByIdAsync(params object[] id)
            => await _uow.GetPartialRepository<Questionnaire>().GetByIdAsync(id);

        public void Dispose()
            => _uow.Dispose();

        public async Task<int> GetItemsAmountAsync()
            => await _uow.GetPartialRepository<Questionnaire>().CountAsync();

        public async Task<IEnumerable<Questionnaire>> GetPagedListAsync(int page, int pageSize)
        {
            var items = await _uow.GetPartialRepository<Questionnaire>().ListAsync();
            return items.Skip((page - 1) * pageSize).Take(pageSize).ToList();
        }
    }
}