function addComparedValue() {
  $.ajax({
    url: '/validation/add-compared-value',
    type: 'POST',
    async: true,

    success: function (data) {
      $('#compared-values').append(data);
    },
    error: function(xhr) {
      alert('Ajax request failed.');
      console.log(xhr.responseText);
    }
  });
  return false;
}

function addQuestionnaire() {
  $.ajax({
    url: '/validation/add-questionnaire',
    type: 'POST',
    async: true,

    success: function (data) {
      $('#questionnaires').append(data);
    },
    error: function (xhr) {
      alert('Ajax request failed.');
      console.log(xhr.responseText);
    }
  });
  return false;
}

function saveValidation() {
  $(document).ready(function () {
    $('#create-validation-btn').click(function (e) {
      e.preventDefault();

      var validation = {}
      // проверяемый ответ
      validation.title = $('#create_validation_title').val();
      validation.answer = {};
      validation.answer.code = $('#create_validation_answerCode').val();
      validation.answer.typeId = $('#create_validation_answerType').val();
      validation.answer.indicatorId = $('#create_validation_answerIndicator').val();
      // сравниваемые значения
      validation.comparedValues = {};
      validation.comparedValues.compareOperatorId = $('#create_validation_compareOperator').val();
      validation.comparedValues.values = [];

      validation.comparedValues.values.push({
        logicOperator: null,
        value: $('#create_validation_comparedValue').val(),
        typeId: $('#create_validation_comparedValueType').val()
      });

      var _comparedValues = $('.compared-value');
      for (var i = 0; i < _comparedValues.length; i++) {
        validation.comparedValues.values.push({
          logicOperator: _comparedValues[i].getElementsByClassName('compared-value-operator-id')[0].value,
          value: _comparedValues[i].getElementsByClassName('compared-value-input')[0].value,
          typeId: _comparedValues[i].getElementsByClassName('compared-value-type-id')[0].value
        });
      }
      // связный ответ
      validation.relatedAnswer = {};
      var _relatedAnswer = $('#create_validation_relAnswerCode').val();
      if (_relatedAnswer != null && _relatedAnswer.length > 0) {
        validation.relatedAnswer.code = _relatedAnswer;
        validation.relatedAnswer.compareOperatorId = $('#create_validation_relAnswerCompareOperator').val();
        validation.relatedAnswer.value = $('#create_validation_relAnswerValue').val();
        validation.relatedAnswer.typeId = $('#create_validation_relAnswerType').val();
      } else {
        validation.relatedAnswer = null;
      }
      // опросники
      validation.questionnaires = [];
      validation.questionnaires.push($('#create_validation_questionnaireId').val());

      var _questionnaires = $('.questionnaire');
      for (var q = 0; q < _questionnaires.length; q++) {
        validation.questionnaires.push(
          _questionnaires[q].getElementsByClassName('questionnaire-id')[0].value
        );
      }

      $.ajax({
        url: '/validation/create',
        type: 'POST',
        dataType: 'text',
        data: JSON.stringify(validation),

        success: function (respond) {
          alert('Validation saved successfuly.');
        },
        error: function (xhr) {
          alert('Ajax request failed.');
          console.log(xhr.responseText);
        }
      });

    });
  });
}